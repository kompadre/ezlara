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
        Schema::create('tiendas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->unique('nombre');
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->unique('nombre');
            $table->timestamps();
        });

        Schema::create('tiendas_productos', function (Blueprint $table) {
            $table->integer('tienda_id')->unsigned();
            $table->integer('producto_id')->unsigned();
            $table->foreign('tienda_id')->references('id')->on('tiendas')->onDelete('restrict');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('restrict');
            $table->integer('cantidad')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiendas_productos');
        Schema::dropIfExists('tiendas');
        Schema::dropIfExists('productos');
    }
};
