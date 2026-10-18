<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE brands (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    created_at DATETIME,
    updated_at DATETIME
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
