<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'attendances';

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
        'login_time',
        'logout_time',
        'device_info',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'login_time'  => 'datetime',
        'logout_time' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['work_duration_minutes'];

    /**
     * Get the work duration in minutes (login → logout).
     * Returns null if the session is still open.
     */
    public function getWorkDurationMinutesAttribute(): ?int
    {
        if ($this->login_time && $this->logout_time) {
            return (int) $this->login_time->diffInMinutes($this->logout_time);
        }

        return null;
    }

    /**
     * An attendance record belongs to a branch user (employee).
     */
    public function branchUser(): BelongsTo
    {
        return $this->belongsTo(BranchUser::class, 'branch_user_id');
    }

    /**
     * An attendance record belongs to a company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * An attendance record belongs to a branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
