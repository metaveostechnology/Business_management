<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminController extends Controller
{
    public function __construct(
        protected AdminService $adminService
    ) {}

    public function index(Request $request)
    {
        try {
            $admins = $this->adminService->getAdmins(
                search: $request->query('search'),
                status: $request->query('status'),
                perPage: (int) $request->query('per_page', 10)
            );

            return view('admins.index', compact('admins'));
        } catch (Throwable $e) {
            Log::error('Web Admin index error', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while fetching admins.');
        }
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(CreateAdminRequest $request)
    {
        try {
            $this->adminService->createAdmin($request->validated());
            return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
        } catch (Throwable $e) {
            Log::error('Web Admin store error', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An error occurred while creating the admin.');
        }
    }

    public function show(string $slug)
    {
        try {
            $admin = $this->adminService->findBySlug($slug);
            if (!$admin) {
                return redirect()->route('admins.index')->with('error', 'Admin not found.');
            }
            return view('admins.show', compact('admin'));
        } catch (Throwable $e) {
            Log::error('Web Admin show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return redirect()->route('admins.index')->with('error', 'An error occurred while fetching the admin.');
        }
    }

    public function edit(string $slug)
    {
        try {
            $admin = $this->adminService->findBySlug($slug);
            if (!$admin) {
                return redirect()->route('admins.index')->with('error', 'Admin not found.');
            }
            return view('admins.edit', compact('admin'));
        } catch (Throwable $e) {
            Log::error('Web Admin edit error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return redirect()->route('admins.index')->with('error', 'An error occurred while fetching the admin.');
        }
    }

    public function update(UpdateAdminRequest $request, string $slug)
    {
        try {
            $admin = $this->adminService->findBySlug($slug);
            if (!$admin) {
                return redirect()->route('admins.index')->with('error', 'Admin not found.');
            }

            $this->adminService->updateAdmin($admin, $request->validated());
            return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
        } catch (Throwable $e) {
            Log::error('Web Admin update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An error occurred while updating the admin.');
        }
    }

    public function destroy(Request $request, string $slug)
    {
        try {
            $admin = $this->adminService->findBySlug($slug);
            if (!$admin) {
                return redirect()->route('admins.index')->with('error', 'Admin not found.');
            }

            if ($admin->id === $request->user()->id) {
                return back()->with('error', 'You cannot delete your own account.');
            }

            $this->adminService->deleteAdmin($admin);
            return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Web Admin destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while deleting the admin.');
        }
    }
}
