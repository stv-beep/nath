<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->username(),
            'name' => $this->faker->name(),
            //'email' => $this->faker->unique()->safeEmail(),
            //'email_verified_at' => now(),
            'magatzem' => $this->faker->boolean(),
            'administrador' => $this->faker->boolean(),
            'DNI' => $this->faker->dni(),
            'id_odoo_nath' => $this->faker->unique()->numberBetween(1,999),
            'id_odoo_tuctuc' => $this->faker->unique()->numberBetween(1,999),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            //'password' => $this->faker->password(),//Hash::make($data['password']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
