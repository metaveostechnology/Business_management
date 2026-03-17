<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class BranchUser extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'branch_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'branch_id',
        'dept_id',
        'emp_id',
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
        'slug',
        'is_dept_admin',
        'is_branch_admin',
        'is_active',
        'is_delete',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_dept_admin'   => 'boolean',
        'is_branch_admin' => 'boolean',
        'is_active'       => 'boolean',
        'is_delete'       => 'boolean',
        'password'        => 'hashed',
    ];

    /**
     * Get the route key for the model (slug-based routing).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * A branch user belongs to a company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * A branch user belongs to a branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * A branch user belongs to a department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
}
