<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE documents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER REFERENCES orders(id) ON DELETE RESTRICT,
    user_id INTEGER REFERENCES users(id) ON DELETE RESTRICT,
    type VARCHAR(30) NOT NULL CHECK (type IN ('invoice', 'sales_report', 'products_report', 'vip_report', 'giveaways_report')),
    name VARCHAR(150) NOT NULL,
    path VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME,
    updated_at DATETIME,
    CHECK (type <> 'invoice' OR order_id IS NOT NULL)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
