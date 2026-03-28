<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeAuthController extends Controller
{
    /**
     * Employee login (branch_user guard).
     *
     * Validates credentials, enforces active/non-deleted status,
     * then auto-creates an attendance record for this session.
     * Any stale open session is closed first (strict-mode safety).
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'data'    => $validator->errors(),
            ], 422);
        }

        // Fetch employee — must belong to a department, be active, and not deleted
        $user = BranchUser::with(['company', 'branch', 'department'])
            ->where('email', $request->email)
            ->whereNotNull('dept_id')
            ->where('dept_id', '>', 0)
            ->where('is_delete', 0)
            ->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials or account not found.',
                'data'    => (object)[],
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'status'  => false,
                'message' => 'Your account is inactive. Please contact your administrator.',
                'data'    => (object)[],
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials.',
                'data'    => (object)[],
            ], 401);
        }

        // Issue Sanctum token with ability scoped to branch_user
        $token = $user->createToken('branch_user_token')->plainTextToken;

        // ── Attendance: close any stale open session (strict-mode) ──────────
        Attendance::where('branch_user_id', $user->id)
            ->whereNull('logout_time')
            ->update(['logout_time' => now()]);

        // ── Attendance: create a fresh login record ──────────────────────────
        Attendance::create([
            'company_id'     => $user->company_id,
            'branch_id'      => $user->branch_id,
            'dept_id'        => $user->dept_id,
            'branch_user_id' => $user->id,
            'login_time'     => now(),
            'device_info'    => $request->header('User-Agent'),
            'ip_address'     => $request->ip(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    /**
     * Employee logout (branch_user guard).
     *
     * Stamps the logout_time on the current open attendance record
     * then revokes the Sanctum token.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if (
            !$user ||
            !$user->dept_id ||
            $user->dept_id <= 0 ||
            $user->is_active != 1 ||
            $user->is_delete != 0
        ) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // ── Attendance: close the active session ─────────────────────────────
        $attendance = Attendance::where('branch_user_id', $user->id)
            ->whereNull('logout_time')
            ->latest()
            ->first();

        if ($attendance) {
            $attendance->update(['logout_time' => now()]);
        }

        // Revoke current token
        $user->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Get the authenticated employee's own profile.
     * Returns the user with company, branch, and department relations.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        if (
            !$user ||
            !$user->dept_id ||
            $user->dept_id <= 0 ||
            $user->is_active != 1 ||
            $user->is_delete != 0
        ) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $user->load(['company', 'branch', 'department']);

        return response()->json([
            'status'  => true,
            'message' => 'Profile fetched successfully',
            'data'    => $user,
        ]);
    }
}
