<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipo_computo', function (Blueprint $table) {
            $table->id();
            $table->boolean('publicado')->default(false);
            $table->date('Fecha_AD')->nullable();
            $table->string('No_Factura', 100)->nullable();
            $table->string('Proveedor', 150)->nullable();
            $table->string('Concepto', 200)->nullable();
            $table->text('especificaciones')->nullable();
            $table->string('foto_path')->nullable();
            $table->integer('Cantidad')->nullable()->default(1);
            $table->string('Espacio', 150)->nullable();
            $table->text('Observacion')->nullable();
            $table->decimal('MOI', 15, 2)->nullable()->default(0.00);
            $table->string('factura_path')->nullable();
            $table->string('QR_Code')->nullable();
            $table->string('Usuario', 150)->nullable();
            $table->string('Folio_Externo', 100)->nullable();
            $table->string('RFC', 20)->nullable();
            $table->string('Razon_Social', 200)->nullable();
            $table->string('Factura_XML')->nullable();
            $table->string('Factura_PDF')->nullable();
            $table->decimal('MOI_Total', 15, 2)->storedAs('MOI * Cantidad')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER calcular_moi_total_insert_equipo_computo
            BEFORE INSERT ON equipo_computo
            FOR EACH ROW
            BEGIN
                SET NEW.MOI_Total = IFNULL(NEW.MOI,0) * IFNULL(NEW.Cantidad,0);
            END
        ');

        DB::unprepared('
            CREATE TRIGGER calcular_moi_total_update_equipo_computo
            BEFORE UPDATE ON equipo_computo
            FOR EACH ROW
            BEGIN
                SET NEW.MOI_Total = IFNULL(NEW.MOI,0) * IFNULL(NEW.Cantidad,0);
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_computo');
    }
};
