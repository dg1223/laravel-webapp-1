<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// use App\Models\Link;
// use Faker\Generator as Faker;

class LinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => substr($this->faker->sentence(2), 0, -1),
            'url' => $this->faker->url,
            'description' => $this->faker->paragraph,
        ];
    }
}

// $factory->define(Link::class, function (Faker $faker) {
//     return [
        // 'title' => substr($faker->sentence(2), 0, -1),
        // 'url' => $faker->url,
        // 'description' => $faker->paragraph,
//     ];
// });