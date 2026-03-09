<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        $slug = Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999);

        return [
            'slug'                => $slug,
            'code'                => strtoupper($this->faker->unique()->lexify('??????')),
            'name'                => $name,
            'legal_name'          => $name . ' Ltd',
            'email'               => $this->faker->unique()->companyEmail(),
            'phone'               => $this->faker->numerify('##########'), // 10 digits
            'website'             => $this->faker->url(),
            'tax_number'          => $this->faker->bothify('GSTIN##??##??'),
            'registration_number' => $this->faker->bothify('REG######'),
            'currency_code'       => 'INR',
            'timezone'            => 'Asia/Kolkata',
            'address_line1'       => $this->faker->streetAddress(),
            'address_line2'       => $this->faker->secondaryAddress(),
            'city'                => $this->faker->city(),
            'state'               => $this->faker->state(),
            'country'             => 'India',
            'postal_code'         => $this->faker->postcode(),
            'logo_path'           => null,
            'is_active'           => true,
        ];
    }
}
