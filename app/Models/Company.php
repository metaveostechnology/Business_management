<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
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
        'logo_path',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the route key for the model (slug-based routing).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
