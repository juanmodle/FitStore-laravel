<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE discounts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(20) NOT NULL CHECK (type IN ('fixed', 'percentage')),
    value INTEGER NOT NULL CHECK (typeof(value) = 'integer' AND value > 0),
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL CHECK (ends_at > starts_at),
    is_active BOOLEAN NOT NULL DEFAULT 1 CHECK (is_active IN (0, 1)),
    vip_only BOOLEAN NOT NULL DEFAULT 0 CHECK (vip_only IN (0, 1)),
    created_at DATETIME,
    updated_at DATETIME,
    CHECK (type <> 'percentage' OR value <= 100)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
