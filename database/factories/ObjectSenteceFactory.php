<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ObjectSentece;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ObjectSentece>
 */
class ObjectSenteceFactory extends Factory
{
    protected $model = ObjectSentece::class;

    public function definition(): array
    {
        return [
            'sentence' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image' => 'uploads/category/test.jpg'
        ];
    }
}
