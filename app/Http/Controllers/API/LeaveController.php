<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    // ── Annual leave allowance (days) — adjust or move to config / settings ──
    const ANNUAL_ALLOWANCE = 24;

    /**
     * Apply for leave.
     *
     * POST /employee/leave/apply
     *
     * Total days is calculated server-side — never trusted from the client.
     * branch_user_id is always taken from auth()->id() for security.
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leave_type' => 'nullable|string|max:50',
            'from_date'  => 'required|date_format:Y-m-d|after_or_equal:today',
            'to_date'    => 'required|date_format:Y-m-d|after_or_equal:from_date',
            'reason'     => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'data'    => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Calculate total days (inclusive of both from & to date)
        $totalDays = Carbon::parse($request->from_date)
            ->diffInDays(Carbon::parse($request->to_date)) + 1;

        // Check for overlapping pending/approved leave
        $overlap = Leave::where('branch_user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('from_date', [$request->from_date, $request->to_date])
                  ->orWhereBetween('to_date', [$request->from_date, $request->to_date])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('from_date', '<=', $request->from_date)
                         ->where('to_date', '>=', $request->to_date);
                  });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'status'  => false,
                'message' => 'You already have a pending or approved leave that overlaps with these dates.',
            ], 422);
        }

        $leave = Leave::create([
            'company_id'     => $user->company_id,
            'branch_id'      => $user->branch_id,
            'dept_id'        => $user->dept_id,
            'branch_user_id' => $user->id,
            'leave_type'     => $request->leave_type,
            'from_date'      => $request->from_date,
            'to_date'        => $request->to_date,
            'total_days'     => $totalDays,
            'reason'         => $request->reason,
            'status'         => 'pending',
        ]);

        $leave->load(['employee', 'company', 'branch', 'department', 'approver']);

        return response()->json([
            'status'  => true,
            'message' => 'Leave application submitted successfully',
            'data'    => $leave,
        ], 201);
    }

    /**
     * View own leave history (paginated, with optional filters).
     *
     * GET /employee/leaves
     *
     * Optional query params:
     *   ?status=pending|approved|rejected
     *   ?from_date=YYYY-MM-DD
     *   ?to_date=YYYY-MM-DD
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'nullable|in:pending,approved,rejected',
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date'   => 'nullable|date_format:Y-m-d|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid filter parameters',
                'data'    => $validator->errors(),
            ], 422);
        }

        // Always scope to authenticated employee only
        $query = Leave::with(['employee', 'company', 'branch', 'department', 'approver'])
            ->where('branch_user_id', auth()->id())
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->where('from_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('to_date', '<=', $request->to_date);
        }

        $leaves = $query->paginate(10);

        return response()->json([
            'status'  => true,
            'message' => 'Leave history fetched successfully',
            'data'    => $leaves,
        ]);
    }

    /**
     * Get own leave balance summary.
     *
     * GET /employee/leave-balance
     */
    public function balance(Request $request)
    {
        $userId = auth()->id();

        $currentYear = Carbon::now()->year;

        // Total approved days used this year
        $usedDays = Leave::where('branch_user_id', $userId)
            ->where('status', 'approved')
            ->whereYear('from_date', $currentYear)
            ->sum('total_days');

        // Pending days (applied but not yet decided)
        $pendingDays = Leave::where('branch_user_id', $userId)
            ->where('status', 'pending')
            ->whereYear('from_date', $currentYear)
            ->sum('total_days');

        $allowance  = self::ANNUAL_ALLOWANCE;
        $remaining  = max(0, $allowance - $usedDays);

        return response()->json([
            'status'  => true,
            'message' => 'Leave balance fetched successfully',
            'data'    => [
                'year'                   => $currentYear,
                'annual_allowance_days'  => $allowance,
                'used_days'              => (int) $usedDays,
                'pending_days'           => (int) $pendingDays,
                'remaining_days'         => $remaining,
            ],
        ]);
    }

    /**
     * View a single leave application belonging to the authenticated employee.
     *
     * GET /employee/leaves/{id}
     */
    public function show(int $id)
    {
        $leave = Leave::with(['employee', 'company', 'branch', 'department', 'approver'])
            ->where('branch_user_id', auth()->id())
            ->where('id', $id)
            ->first();

        if (!$leave) {
            return response()->json([
                'status'  => false,
                'message' => 'Leave record not found',
                'data'    => (object)[],
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Leave record fetched successfully',
            'data'    => $leave,
        ]);
    }

    /**
     * Cancel a pending leave application.
     *
     * DELETE /employee/leaves/{id}
     *
     * Only pending leaves can be cancelled by the employee.
     */
    public function cancel(int $id)
    {
        $leave = Leave::where('branch_user_id', auth()->id())
            ->where('id', $id)
            ->first();

        if (!$leave) {
            return response()->json([
                'status'  => false,
                'message' => 'Leave record not found',
            ], 404);
        }

        if ($leave->status !== 'pending') {
            return response()->json([
                'status'  => false,
                'message' => 'Only pending leaves can be cancelled. This leave is already ' . $leave->status . '.',
            ], 422);
        }

        $leave->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Leave application cancelled successfully',
        ]);
    }
}
