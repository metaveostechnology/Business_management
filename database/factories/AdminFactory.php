<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name     = $this->faker->unique()->name();
        $baseSlug = Str::slug($name);
        // Append a unique number to guarantee no slug collision across test cases
        $slug     = $baseSlug . '-' . $this->faker->unique()->numberBetween(1000, 9999);

        return [
            'slug'          => $slug,
            'name'          => $name,
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->phoneNumber(),
            'username'      => $this->faker->unique()->userName(),
            'password'      => Hash::make('password'),
            'last_login_at' => $this->faker->optional()->dateTimeBetween('-6 months', 'now'),
            'last_login_ip' => $this->faker->optional()->ipv4(),
            'status'        => $this->faker->randomElement(['active', 'inactive', 'blocked']),
        ];
    }

    /**
     * Indicate the admin is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate the admin is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Indicate the admin is blocked.
     */
    public function blocked(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'blocked',
        ]);
    }
}
