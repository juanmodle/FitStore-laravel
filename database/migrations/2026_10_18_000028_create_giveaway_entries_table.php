<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE giveaway_entries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    giveaway_id INTEGER NOT NULL REFERENCES giveaways(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    joined_at DATETIME NOT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE (giveaway_id, user_id)
)
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER giveaway_entries_keep_winner BEFORE DELETE ON giveaway_entries
WHEN EXISTS (SELECT 1 FROM giveaways WHERE id = OLD.giveaway_id AND winner_user_id = OLD.user_id)
BEGIN
    SELECT RAISE(ABORT, 'No se puede eliminar la participación ganadora');
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER giveaway_entries_keep_winner_update BEFORE UPDATE OF user_id, giveaway_id ON giveaway_entries
WHEN EXISTS (SELECT 1 FROM giveaways WHERE id = OLD.giveaway_id AND winner_user_id = OLD.user_id)
AND (NEW.user_id <> OLD.user_id OR NEW.giveaway_id <> OLD.giveaway_id)
BEGIN
    SELECT RAISE(ABORT, 'No se puede cambiar la participación ganadora');
END
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('giveaway_entries');
    }
};
