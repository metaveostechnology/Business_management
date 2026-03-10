<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'branch_id',
        'slug',
        'parent_department_id',
        'code',
        'name',
        'description',
        'head_user_id',
        'level_no',
        'reports_to_department_id',
        'approval_mode',
        'escalation_mode',
        'can_create_tasks',
        'can_receive_tasks',
        'is_system_default',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_create_tasks' => 'boolean',
        'can_receive_tasks' => 'boolean',
        'is_system_default' => 'boolean',
        'is_active' => 'boolean',
        'level_no' => 'integer',
    ];

    /**
     * Get the route key for the model (slug-based routing).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /**
     * The company this department belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class , 'company_id');
    }

    /**
     * The branch this department is assigned to.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }

    /**
     * The parent department (hierarchical).
     */
    public function parentDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class , 'parent_department_id');
    }

    /**
     * All direct child departments.
     */
    public function childDepartments(): HasMany
    {
        return $this->hasMany(Department::class , 'parent_department_id');
    }

    /**
     * The department this one reports to.
     */
    public function reportsToDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class , 'reports_to_department_id');
    }
}
