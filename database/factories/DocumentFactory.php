<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'user_id' => fn (array $attributes) => Order::findOrFail($attributes['order_id'])->user_id,
            'type' => 'invoice',
            'name' => 'Factura de prueba',
            'path' => 'documents/'.fake()->unique()->bothify('factura-????-####').'.pdf',
        ];
    }
}
