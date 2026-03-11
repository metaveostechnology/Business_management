<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchUser extends Model
{
    use HasFactory;

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
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'slug',
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
        'is_active' => 'boolean',
        'is_delete' => 'boolean',
        'password'  => 'hashed',
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
     * A branch user belongs to a role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
