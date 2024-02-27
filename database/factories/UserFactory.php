<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Factories\AddressFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $address = Address::inRandomOrder()->first() ?? AddressFactory::new()->create();

    return [
      'name' => fake()->name(),
      'motherName' => fake()->name(),
      'birthDate' => fake()->date(),
      'cpf' => fake()->unique()->numerify('###########'),
      'cns' => fake()->unique()->numerify('###############'),
      'imageUrl' => fake()->imageUrl(),
      'address_id' => $address->id,
    ];
  }
}
