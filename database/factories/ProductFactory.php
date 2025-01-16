<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){
		return [
 'title' => $this->faker->unique()->words(4,true),
 'code' => $this->faker->unique()->numerify('########'),
 'description' => $this->faker->realText(150),
 'price' => $this->faker->randomFloat (2,5,250),
 'image' => 'defaultImage.jpg',
 ];
}

}
