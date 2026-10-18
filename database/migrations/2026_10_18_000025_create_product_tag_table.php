<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE product_tag (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    tag_id INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
    priority INTEGER NOT NULL DEFAULT 0 CHECK (typeof(priority) = 'integer' AND priority >= 0),
    is_visible BOOLEAN NOT NULL DEFAULT 1 CHECK (is_visible IN (0, 1)),
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE (product_id, tag_id)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tag');
    }
};
