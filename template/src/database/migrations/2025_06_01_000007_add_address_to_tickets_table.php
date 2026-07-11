<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('address')->nullable()->after('description');
            $table->string('district')->nullable()->after('address');   // Kecamatan
            $table->string('city')->nullable()->after('district');       // Kota
            $table->string('postal_code', 10)->nullable()->after('city');
            $table->text('address_notes')->nullable()->after('postal_code'); // Patokan
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['address', 'district', 'city', 'postal_code', 'address_notes']);
        });
    }
};
