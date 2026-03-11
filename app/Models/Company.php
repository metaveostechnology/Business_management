<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'code',
        'name',
        'legal_name',
        'email',
        'phone',
        'password',
        'website',
        'tax_number',
        'registration_number',
        'currency_code',
        'timezone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
        'address',
        'logo_path',
        'is_active',
        'is_delete',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * A company has many branches.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'company_id');
    }
}
