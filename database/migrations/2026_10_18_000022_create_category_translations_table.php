<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE category_translations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL REFERENCES categories(id) ON DELETE CASCADE,
    locale VARCHAR(2) NOT NULL CHECK (locale IN ('es', 'en')),
    name VARCHAR(100) NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE (category_id, locale)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('category_translations');
    }
};
