<?php

namespace App\Services;

use App\Models\Admin;
use App\Repositories\AdminRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminService
{
    public function __construct(
        protected AdminRepository $adminRepository
    ) {}

    /**
     * Get paginated list of admins.
     */
    public function getAdmins(string $search = null, string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->adminRepository->getAll($search, $status, $perPage);
    }

    /**
     * Find admin by slug.
     */
    public function findBySlug(string $slug): ?Admin
    {
        return $this->adminRepository->findBySlug($slug);
    }

    /**
     * Create a new admin.
     */
    public function createAdmin(array $data): Admin
    {
        $data['slug']     = $this->generateUniqueSlug($data['name']);
        $data['password'] = Hash::make($data['password']);

        return $this->adminRepository->create($data);
    }

    /**
     * Update an existing admin.
     */
    public function updateAdmin(Admin $admin, array $data): Admin
    {
        // Regenerate slug if name changed
        if (isset($data['name']) && $data['name'] !== $admin->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $admin->id);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->adminRepository->update($admin, $data);
    }

    /**
     * Update the authenticated admin's own profile.
     */
    public function updateProfile(Admin $admin, array $data): Admin
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->adminRepository->update($admin, $data);
    }

    /**
     * Soft-delete an admin.
     */
    public function deleteAdmin(Admin $admin): bool
    {
        return $this->adminRepository->delete($admin);
    }

    /**
     * Restore a soft-deleted admin.
     */
    public function restoreAdmin(string $slug): ?Admin
    {
        return $this->adminRepository->restore($slug);
    }

    /**
     * Attempt to log in an admin using email/username + password.
     * Returns the Sanctum token string on success, or null on failure.
     */
    public function attemptLogin(string $login, string $password, string $ip): ?array
    {
        $admin = $this->adminRepository->findByEmailOrUsername($login);

        if (!$admin || !Hash::check($password, $admin->password)) {
            return null;
        }

        if ($admin->status !== 'active') {
            return null;
        }

        // Update login metadata
        $admin->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        $token = $admin->createToken('admin-api-token')->plainTextToken;

        return [
            'admin' => $admin->fresh(),
            'token' => $token,
        ];
    }

    /**
     * Generate a unique slug from a name.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 2;

        while ($this->adminRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
