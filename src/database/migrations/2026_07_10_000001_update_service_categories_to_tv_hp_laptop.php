<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE technicians MODIFY skill_category VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE tickets MODIFY category VARCHAR(20) NOT NULL');

        DB::table('technicians')->where('skill_category', 'KULKAS')->update(['skill_category' => 'LAPTOP']);
        DB::table('technicians')->where('skill_category', 'MESIN_CUCI')->update(['skill_category' => 'HP']);

        DB::table('tickets')->where('category', 'KULKAS')->update(['category' => 'LAPTOP']);
        DB::table('tickets')->where('category', 'MESIN_CUCI')->update(['category' => 'HP']);

        DB::statement("ALTER TABLE technicians MODIFY skill_category ENUM('TV', 'HP', 'LAPTOP') NOT NULL");
        DB::statement("ALTER TABLE tickets MODIFY category ENUM('TV', 'HP', 'LAPTOP') NOT NULL");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE technicians MODIFY skill_category VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE tickets MODIFY category VARCHAR(20) NOT NULL');

        DB::table('technicians')->where('skill_category', 'LAPTOP')->update(['skill_category' => 'KULKAS']);
        DB::table('technicians')->where('skill_category', 'HP')->update(['skill_category' => 'MESIN_CUCI']);

        DB::table('tickets')->where('category', 'LAPTOP')->update(['category' => 'KULKAS']);
        DB::table('tickets')->where('category', 'HP')->update(['category' => 'MESIN_CUCI']);

        DB::statement("ALTER TABLE technicians MODIFY skill_category ENUM('KULKAS', 'TV', 'MESIN_CUCI') NOT NULL");
        DB::statement("ALTER TABLE tickets MODIFY category ENUM('KULKAS', 'TV', 'MESIN_CUCI') NOT NULL");
    }
};
