<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('ALTER TABLE jadwal_siswa MODIFY id_jadwal_siswa MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('ALTER TABLE jadwal_siswa MODIFY id_jadwal_siswa MEDIUMINT UNSIGNED NOT NULL');
        Schema::enableForeignKeyConstraints();
    }
};