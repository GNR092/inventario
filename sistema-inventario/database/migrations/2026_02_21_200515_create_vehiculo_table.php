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
        Schema::create('vehiculo', function (Blueprint $table) {
            $table->id();
            $table->date('Fecha_AD')->nullable();
            $table->string('No_Factura', 100)->nullable();
            $table->string('Proveedor')->nullable();
            $table->string('Concepto')->nullable();
            $table->integer('Cantidad')->nullable();
            $table->text('Observacion')->nullable();
            $table->decimal('MOI', 15, 2)->nullable();
            $table->decimal('MOI_Total', 15, 2)->nullable();
            $table->string('Razon_Social')->nullable();
            $table->string('RFC', 20)->nullable();
            $table->string('Folio_Externo', 100)->nullable();
            $table->string('Espacio')->nullable();
            $table->string('Usuario', 150)->nullable();
            $table->string('Numero_Serie', 150)->nullable();
            $table->string('QR_Code')->nullable();
            $table->string('Factura_XML')->nullable();
            $table->string('Factura_PDF')->nullable();
            $table->string('factura_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER calcular_moi_total_insert_vehiculo
            BEFORE INSERT ON vehiculo
            FOR EACH ROW
            BEGIN
                SET NEW.MOI_Total = IFNULL(NEW.MOI,0) * IFNULL(NEW.Cantidad,0);
            END
        ');

        DB::unprepared('
            CREATE TRIGGER calcular_moi_total_update_vehiculo
            BEFORE UPDATE ON vehiculo
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
        Schema::dropIfExists('vehiculo');
    }
};
