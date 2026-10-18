<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE coupon_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    coupon_id INTEGER NOT NULL REFERENCES coupons(id) ON DELETE RESTRICT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    used_at DATETIME,
    times_used INTEGER NOT NULL DEFAULT 0 CHECK (typeof(times_used) = 'integer' AND times_used >= 0),
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE (coupon_id, user_id)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
    }
};
