<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DeptEmployeeAuthController extends Controller
{
    /**
     * Login for department employee.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $user = BranchUser::with(['company', 'branch', 'department'])
            ->where('email', $request->email)
            
            ->whereNotNull('dept_id')
            ->where('dept_id', '>', 0)
            ->where('is_delete', 0)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials or you are a Department/Branch Admin. Please use the appropriate admin login page.',
                'data' => (object)[]
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact your Department Admin.',
                'data' => (object)[]
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
                'data' => (object)[]
            ], 401);
        }


        $token = $user->createToken('dept_employee')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout a department employee.
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
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Safe logout
        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Change password for a department employee.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (
            !$user ||
            
            !$user->dept_id ||
            $user->dept_id <= 0 ||
            $user->is_active != 1 ||
            $user->is_delete != 0
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Logout from all devices after password change
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}
