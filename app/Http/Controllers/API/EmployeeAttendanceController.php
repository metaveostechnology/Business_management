<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeAttendanceController extends Controller
{
    /**
     * Get the authenticated employee's own attendance history.
     *
     * Supports optional filters:
     *   ?from_date=YYYY-MM-DD
     *   ?to_date=YYYY-MM-DD
     *
     * Each record includes a computed `work_duration_minutes` field.
     */
    public function index(Request $request)
    {
        // Validate optional filter params
        $validator = Validator::make($request->all(), [
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date'   => 'nullable|date_format:Y-m-d|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid date filter',
                'data'    => $validator->errors(),
            ], 422);
        }

        // Always scope to the authenticated employee — never accept user_id from request
        $query = Attendance::where('branch_user_id', auth()->id())
            ->latest('login_time');

        // ── Optional date filters ────────────────────────────────────────────
        if ($request->filled('from_date')) {
            $query->whereDate('login_time', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('login_time', '<=', $request->to_date);
        }

        $data = $query->paginate(10);

        return response()->json([
            'status'  => true,
            'message' => 'Attendance fetched successfully',
            'data'    => $data,
        ]);
    }

    /**
     * Get a single attendance record belonging to the authenticated employee.
     */
    public function show(int $id)
    {
        $attendance = Attendance::where('branch_user_id', auth()->id())
            ->where('id', $id)
            ->first();

        if (!$attendance) {
            return response()->json([
                'status'  => false,
                'message' => 'Attendance record not found',
                'data'    => (object)[],
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Attendance record fetched successfully',
            'data'    => $attendance,
        ]);
    }
}
