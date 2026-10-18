<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL REFERENCES categories(id) ON DELETE RESTRICT,
    brand_id INTEGER NOT NULL REFERENCES brands(id) ON DELETE RESTRICT,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT 1 CHECK (is_active IN (0, 1)),
    is_featured BOOLEAN NOT NULL DEFAULT 0 CHECK (is_featured IN (0, 1)),
    deleted_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME
)
SQL);

        Schema::table('products', function (Blueprint $table) {
            $table->index(['category_id', 'is_active']);
            $table->index(['brand_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
