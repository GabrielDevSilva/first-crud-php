<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'cep' => fake()->unique()->numerify('########'),
      'address' => fake()->streetAddress,
      'number' => fake()->buildingNumber,
      'complement' => fake()->secondaryAddress,
      'province' => fake()->word,
      'city' => fake()->city,
      'state' => fake()->state,
    ];
  }
}
