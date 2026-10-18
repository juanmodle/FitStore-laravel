<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE product_translations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    locale VARCHAR(2) NOT NULL CHECK (locale IN ('es', 'en')),
    name VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE (product_id, locale)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
