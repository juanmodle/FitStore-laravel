<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(DatabaseMigrations::class);

it('reconstruye desde cero los mismos datos de negocio', function () {
    expect(DB::getDatabaseName())->toBe(':memory:');
    $this->artisan('migrate:fresh', ['--seed' => true])->assertExitCode(0);

    $before = [];
    foreach (Schema::getTableListing() as $table) {
        if ($table === 'migrations' || str_starts_with($table, 'sqlite_')) {
            continue;
        }
        $before[$table] = DB::table($table)->get()->map(function ($row) {
            // Los hashes tienen una sal aleatoria aunque la contraseña sea igual.
            unset($row->password, $row->remember_token);

            return (array) $row;
        })->all();
    }

    $this->artisan('migrate:fresh', ['--seed' => true])->assertExitCode(0);

    foreach ($before as $table => $rows) {
        $after = DB::table($table)->get()->map(function ($row) {
            unset($row->password, $row->remember_token);

            return (array) $row;
        })->all();

        expect($after)->toBe($rows);
    }

    expect(DB::select('PRAGMA foreign_key_check'))->toBeEmpty();
});
