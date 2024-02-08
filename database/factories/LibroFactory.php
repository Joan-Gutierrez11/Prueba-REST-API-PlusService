<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Libro>
 */
class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'libro' => $this->faker->name(),
            'cantidad' => $this->faker->numberBetween(1, 20),
            'localidad' => $this->faker->randomElement(['Guayaquil', 'Quito', 'Cuenca', 'Loja']),
            'direccion' => $this->faker->address(),
            'categoria' => $this->faker->word(),
            'fecha_lanzamiento' => $this->faker->dateTime()
        ];
    }
}
