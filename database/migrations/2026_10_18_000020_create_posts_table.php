<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    post_category_id INTEGER NOT NULL REFERENCES post_categories(id) ON DELETE RESTRICT,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft' CHECK (status IN ('draft', 'published')),
    published_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME,
    CHECK (status <> 'published' OR published_at IS NOT NULL)
)
SQL);

        Schema::table('posts', function (Blueprint $table) {
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
