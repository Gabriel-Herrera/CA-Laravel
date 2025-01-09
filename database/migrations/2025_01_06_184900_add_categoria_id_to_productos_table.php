<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriaIdToProductosTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->after('imagen');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
        });

        // Actualizar los datos existentes
        DB::statement("UPDATE productos SET categoria_id = (SELECT id FROM categorias WHERE categorias.nombre = productos.categoria)");

        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('categoria')->nullable();
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
}
