<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'leaves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'branch_id',
        'dept_id',
        'branch_user_id',
        'leave_type',
        'from_date',
        'to_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from_date'   => 'date',
        'to_date'     => 'date',
        'approved_at' => 'datetime',
        'total_days'  => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * A leave belongs to a branch user (employee).
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(BranchUser::class, 'branch_user_id');
    }

    /**
     * A leave belongs to a company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * A leave belongs to a branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * A leave belongs to a department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * The approver (another branch user / admin).
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(BranchUser::class, 'approved_by');
    }
}
