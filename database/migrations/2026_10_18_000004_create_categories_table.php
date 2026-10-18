<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    is_active BOOLEAN NOT NULL DEFAULT 1 CHECK (is_active IN (0, 1)),
    created_at DATETIME,
    updated_at DATETIME
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
