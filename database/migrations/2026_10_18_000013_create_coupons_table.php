<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE coupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    discount_id INTEGER NOT NULL REFERENCES discounts(id) ON DELETE RESTRICT,
    code VARCHAR(50) NOT NULL UNIQUE,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL CHECK (ends_at > starts_at),
    minimum_order_cents INTEGER NOT NULL DEFAULT 0 CHECK (typeof(minimum_order_cents) = 'integer' AND minimum_order_cents >= 0),
    max_uses INTEGER CHECK (max_uses IS NULL OR (typeof(max_uses) = 'integer' AND max_uses > 0)),
    max_uses_per_user INTEGER NOT NULL DEFAULT 1 CHECK (typeof(max_uses_per_user) = 'integer' AND max_uses_per_user > 0),
    is_active BOOLEAN NOT NULL DEFAULT 1 CHECK (is_active IN (0, 1)),
    created_at DATETIME,
    updated_at DATETIME
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
