<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JornadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dia'=> $this->faker->dateTimeThisDecade(),
            'treballador'=> $this->faker->numberBetween(1, 200),
            'total'=>$this->faker->randomNumber(1),
        ];
    }
}
