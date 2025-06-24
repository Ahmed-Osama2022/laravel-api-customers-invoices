<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $type = $this->faker->randomElement(['I', 'B']); // (I) for Individual || (B) for Bussnies
    $name = $type == 'I' ? $this->faker->name() : $this->faker->company();

    return [
      'name' => $name,
      'type' => $type,
      // 'email' => fake()->unique()->safeEmail(),
      'email' => $this->faker->email(),
      'state' => $this->faker->state(),
      'city' => $this->faker->city(),
      'address' => $this->faker->address(),
      'postal_code' => $this->faker->postcode(),
    ];
  }
}
