<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BranchAdminAuthController extends Controller
{
    /**
     * Login Branch Admin
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        // Find user from branch_users table
        $user = BranchUser::where('email', $request->email)
            ->where('is_branch_admin', 1)
            ->where('is_active', 1)
            ->where('is_delete', 0)
            ->first();

        // If user not found return error response
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials or user not found.',
                'data' => (object)[]
            ], 404);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid password.',
                'data' => (object)[]
            ], 401);
        }

        // If login successful create Sanctum token
        $token = $user->createToken('branch_admin_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout Branch Admin
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get the authenticated branch admin's own profile.
     * Returns the user with company, branch, and department relations.
     */
    public function profile(Request $request)
    {
        $user = $request->user()
            ->load(['company', 'branch', 'department']);

        return response()->json([
            'status'  => true,
            'message' => 'Profile fetched successfully',
            'data'    => $user,
        ]);
    }
}
