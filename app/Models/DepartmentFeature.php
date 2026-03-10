<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentFeature extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'department_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
        'feature_id',
        'slug',
        'access_level',
        'is_enabled',
        'assigned_by',
        'assigned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'assigned_at' => 'datetime',
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
     * The department this feature mapping belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class , 'department_id');
    }

    /**
     * The feature that is mapped.
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class , 'feature_id');
    }
}
