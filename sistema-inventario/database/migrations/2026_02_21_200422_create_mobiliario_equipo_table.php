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
        Schema::create('mobiliario_equipo', function (Blueprint $table) {
            $table->increments('id');
            $table->date('Fecha_AD')->nullable();
            $table->string('No_Factura', 100)->nullable();
            $table->string('Proveedor')->nullable();
            $table->text('Concepto')->nullable();
            $table->integer('Cantidad')->nullable();
            $table->string('Espacio', 100)->nullable();
            $table->text('Observacion')->nullable();
            $table->decimal('MOI', 18, 2)->nullable();
            $table->decimal('MOI_Total', 15, 2)->nullable()->default(0.00);
            $table->string('Estado', 50)->nullable();
            $table->string('Numero_Serie', 150)->nullable();
            $table->string('QR_Code')->nullable();
            $table->string('Mes_Compra', 20)->nullable();
            $table->integer('Anio_Compra')->nullable();
            $table->text('Comentarios')->nullable();
            $table->string('factura_path')->nullable();
            $table->string('Usuario')->nullable();
            $table->string('Folio_Externo')->nullable();
            $table->string('RFC', 20)->nullable();
            $table->string('Razon_Social')->nullable();
            $table->string('Factura_XML')->nullable();
            $table->string('Factura_PDF')->nullable();
        });

        DB::unprepared('
            CREATE TRIGGER calcular_moi_total_insert_mobiliario_equipo
            BEFORE INSERT ON mobiliario_equipo
            FOR EACH ROW
            BEGIN
                SET NEW.MOI_Total = IFNULL(NEW.MOI,0) * IFNULL(NEW.Cantidad,0);
            END
        ');

        DB::unprepared('
            CREATE TRIGGER calcular_moi_total_update_mobiliario_equipo
            BEFORE UPDATE ON mobiliario_equipo
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
        Schema::dropIfExists('mobiliario_equipo');
    }
};
