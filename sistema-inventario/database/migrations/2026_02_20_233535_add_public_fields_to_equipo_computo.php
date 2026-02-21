<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipo_computo', function (Blueprint $table) {
            $table->boolean('publicado')->default(false)->after('id');
            $table->text('especificaciones')->nullable()->after('Concepto');
            $table->string('foto_path')->nullable()->after('especificaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipo_computo', function (Blueprint $table) {
            $table->dropColumn(['publicado', 'especificaciones', 'foto_path']);
        });
    }
};
