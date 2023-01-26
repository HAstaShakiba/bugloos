<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'service' => $this->faker->randomElement(['order-service', 'invoice_service']),
            'protocol' => 'HTTP/1.1',
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
            'route' => $this->faker->randomElement(['orders', 'invoices']),
            'status' => $this->faker->randomElement([201, 422, 500]),
            'called_at' => $this->faker->dateTime()->format('Y-m-d H:i:s')
        ];
    }
}
