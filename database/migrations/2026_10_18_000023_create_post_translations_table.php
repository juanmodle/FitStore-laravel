<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE post_translations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
    locale VARCHAR(2) NOT NULL CHECK (locale IN ('es', 'en')),
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE (post_id, locale)
)
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('post_translations');
    }
};
