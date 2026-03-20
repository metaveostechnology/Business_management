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
        $admin = auth()->user();

        $employees = BranchUser::where('company_id', $admin->company_id)
            ->where('branch_id', $admin->branch_id)
            ->where('dept_id', $admin->dept_id)
            ->where('is_delete', 0)
            ->where('is_branch_admin', 0)
            ->where('is_dept_admin', 0)
            ->orderBy('id', 'desc')
            ->get();

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
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
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

        // Generate emp_id: [CompanyCode]-[Sequence]
        $prefix = trim($company->code) ?: strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $company->name), 0, 3));
        if (strlen($prefix) < 3) {
            $prefix = strtoupper(str_pad($prefix, 3, 'X'));
        }

        $lastEmployee = BranchUser::where('company_id', $admin->company_id)
            ->whereNotNull('emp_id')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastEmployee && $lastEmployee->emp_id) {
            $parts = explode('-', $lastEmployee->emp_id);
            if (count($parts) >= 2) {
                $lastPart = end($parts);
                if (is_numeric($lastPart)) {
                    $nextNumber = intval($lastPart) + 1;
                }
            }
        }

        $empId = $prefix . '-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);

        // Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            
            // Double Storage: Copy to public/storage
            $source = storage_path('app/public/' . $profileImagePath);
            $destination = public_path('storage/' . $profileImagePath);
            $destDir = dirname($destination);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (file_exists($source)) {
                copy($source, $destination);
            }
        }

        try {
            $employee = BranchUser::create([
                'company_id' => $admin->company_id,
                'branch_id' => $admin->branch_id,
                'dept_id' => $admin->dept_id,
                'emp_id' => $empId,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'profile_image' => $profileImagePath,
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
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // Duplicate entry
                return response()->json([
                    'status' => false,
                    'message' => 'Employee ID collision or Email already exists. Please try again.',
                    'debug_error' => $e->getMessage()
                ], 422);
            }
            throw $e;
        }

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
            'name' => 'sometimes|string|max:191',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($employee->profile_image) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($employee->profile_image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->profile_image);
                }
                $publicFilePath = public_path('storage/' . $employee->profile_image);
                if (file_exists($publicFilePath)) {
                    unlink($publicFilePath);
                }
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $employee->profile_image = $path;

            // Double Storage: Copy to public/storage
            $source = storage_path('app/public/' . $path);
            $destination = public_path('storage/' . $path);
            $destDir = dirname($destination);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (file_exists($source)) {
                copy($source, $destination);
            }
        }

        if ($request->has('name')) {
            $employee->name = $request->name;
            if ($employee->isDirty('name')) {
                $employee->slug = $this->generateUniqueSlug($request->name, $employee->id);
            }
        }
        
        if ($request->has('phone')) {
            $employee->phone = $request->phone;
        }
        if ($request->has('is_active')) {
            $employee->is_active = $request->is_active;
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

        while (true) {
            $query = BranchUser::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            
            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

}
