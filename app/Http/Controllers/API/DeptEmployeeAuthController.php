<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            ->where('is_dept_admin', 0)
            ->where('is_branch_admin', 0)
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
}
