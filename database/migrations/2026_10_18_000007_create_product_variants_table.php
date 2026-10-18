<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE product_variants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL REFERENCES products(id) ON DELETE RESTRICT,
    sku VARCHAR(80) NOT NULL UNIQUE,
    size VARCHAR(30),
    color VARCHAR(50),
    flavor VARCHAR(60),
    format VARCHAR(60),
    price_cents INTEGER NOT NULL CHECK (typeof(price_cents) = 'integer' AND price_cents >= 0),
    stock INTEGER NOT NULL DEFAULT 0 CHECK (typeof(stock) = 'integer' AND stock >= 0),
    is_active BOOLEAN NOT NULL DEFAULT 1 CHECK (is_active IN (0, 1)),
    deleted_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
