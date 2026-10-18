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
CREATE TABLE giveaways (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    prize VARCHAR(255) NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL CHECK (ends_at > starts_at),
    status VARCHAR(20) NOT NULL DEFAULT 'draft' CHECK (status IN ('draft', 'active', 'closed')),
    winner_user_id INTEGER REFERENCES users(id) ON DELETE RESTRICT,
    created_at DATETIME,
    updated_at DATETIME
)
SQL);

        Schema::table('giveaways', function (Blueprint $table) {
            $table->index(['status', 'starts_at', 'ends_at']);
        });

        DB::unprepared(<<<'SQL'
CREATE TRIGGER giveaways_check_winner BEFORE UPDATE OF winner_user_id ON giveaways
WHEN NEW.winner_user_id IS NOT NULL
AND NOT EXISTS (SELECT 1 FROM giveaway_entries WHERE giveaway_id = NEW.id AND user_id = NEW.winner_user_id)
BEGIN
    SELECT RAISE(ABORT, 'El ganador debe ser participante');
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER giveaways_check_initial_winner BEFORE INSERT ON giveaways
WHEN NEW.winner_user_id IS NOT NULL
BEGIN
    SELECT RAISE(ABORT, 'Hay que crear el sorteo e inscribir participantes antes de elegir ganador');
END
SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('giveaways');
    }
};
