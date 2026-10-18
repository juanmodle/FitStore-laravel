<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL REFERENCES orders(id) ON DELETE RESTRICT,
    product_variant_id INTEGER NOT NULL REFERENCES product_variants(id) ON DELETE RESTRICT,
    product_name VARCHAR(150) NOT NULL,
    sku VARCHAR(80) NOT NULL,
    quantity INTEGER NOT NULL CHECK (typeof(quantity) = 'integer' AND quantity > 0),
    unit_price_cents INTEGER NOT NULL CHECK (typeof(unit_price_cents) = 'integer' AND unit_price_cents >= 0),
    line_total_cents INTEGER NOT NULL CHECK (line_total_cents = quantity * unit_price_cents),
    created_at DATETIME,
    updated_at DATETIME
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
