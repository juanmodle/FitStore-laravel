<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE product_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    path VARCHAR(255) NOT NULL,
    alt VARCHAR(255) NOT NULL,
    position INTEGER NOT NULL DEFAULT 0 CHECK (typeof(position) = 'integer' AND position >= 0),
    created_at DATETIME,
    updated_at DATETIME
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
