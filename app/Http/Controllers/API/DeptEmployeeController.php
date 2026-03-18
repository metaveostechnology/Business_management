<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchUser;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DeptEmployeeController extends Controller
{
    /**
     * Display a listing of department employees.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = BranchUser::where('company_id', $user->company_id)
            ->where('branch_id', $user->branch_id)
            ->where('dept_id', $user->dept_id)
            ->where('is_delete', 0)
            ->where('is_branch_admin', 0)
            ->where('is_dept_admin', 0);
            
        // Search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Active/inactive filter
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('per_page')) {
            $employees = $query->paginate($request->per_page);
        } else {
            $employees = $query->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Employees retrieved successfully',
            'data' => $employees
        ]);
    }

    /**
     * Store a newly created department employee in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:branch_users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $admin = auth()->user();
        $company = Company::find($admin->company_id);
        
        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found',
                'data' => (object)[]
            ], 404);
        }

        // Generate emp_id
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $company->name), 0, 3));
        if (strlen($prefix) < 3) {
            $prefix = strtoupper(str_pad(substr($company->name, 0, 3), 3, 'X'));
        }

        $lastEmployee = BranchUser::where('company_id', $admin->company_id)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastEmployee && $lastEmployee->emp_id) {
            $parts = explode('-', $lastEmployee->emp_id);
            if (count($parts) == 2 && is_numeric($parts[1])) {
                $nextNumber = intval($parts[1]) + 1;
            } else {
                // fallback if last emp_id format was different
                $lastNum = intval(preg_replace('/[^0-9]/', '', $lastEmployee->emp_id));
                $nextNumber = $lastNum > 0 ? $lastNum + 1 : 1;
            }
        }

        $empId = $prefix . '-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);

        $employee = BranchUser::create([
            'company_id' => $admin->company_id,
            'branch_id' => $admin->branch_id,
            'dept_id' => $admin->dept_id,
            'emp_id' => $empId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'slug' => $this->generateUniqueSlug($request->name),
            'is_branch_admin' => 0,
            'is_dept_admin' => 0,
            'is_active' => 1,
            'is_delete' => 0,
            'created_by' => $admin->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Employee created successfully',
            'data' => $employee
        ], 201);
    }

    /**
     * Display the specified employee.
     */
    public function show($slug)
    {
        $admin = auth()->user();
        $employee = BranchUser::where('company_id', $admin->company_id)
            ->where('branch_id', $admin->branch_id)
            ->where('dept_id', $admin->dept_id)
            ->where('slug', $slug)
            ->where('is_delete', 0)
            ->first();

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found',
                'data' => (object)[]
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Employee retrieved successfully',
            'data' => $employee
        ]);
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, $slug)
    {
        $admin = auth()->user();
        $employee = BranchUser::where('company_id', $admin->company_id)
            ->where('branch_id', $admin->branch_id)
            ->where('dept_id', $admin->dept_id)
            ->where('slug', $slug)
            ->where('is_delete', 0)
            ->first();

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found',
                'data' => (object)[]
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:191',
            'phone' => 'nullable|string|max:20',
            'email' => 'sometimes|required|email|unique:branch_users,email,' . $employee->id,
            'password' => 'nullable|min:6',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        if ($request->has('name')) {
            $employee->name = $request->name;
            if ($employee->isDirty('name')) {
                $employee->slug = $this->generateUniqueSlug($request->name, $employee->id);
            }
        }
        
        if ($request->has('email')) {
            $employee->email = $request->email;
        }
        if ($request->has('phone')) {
            $employee->phone = $request->phone;
        }
        if ($request->has('is_active')) {
            $employee->is_active = $request->is_active;
        }
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee
        ]);
    }

    /**
     * Soft delete the specified employee.
     */
    public function destroy($slug)
    {
        $admin = auth()->user();
        $employee = BranchUser::where('company_id', $admin->company_id)
            ->where('branch_id', $admin->branch_id)
            ->where('dept_id', $admin->dept_id)
            ->where('slug', $slug)
            ->where('is_delete', 0)
            ->first();

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found',
                'data' => (object)[]
            ], 404);
        }

        $employee->is_delete = 1;
        $employee->is_active = 0;
        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully',
            'data' => (object)[]
        ]);
    }

    /**
     * Generate unique slug for branch user
     */
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 2;

        $query = BranchUser::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count;
            $query = BranchUser::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $count++;
        }

        return $slug;
    }
}
