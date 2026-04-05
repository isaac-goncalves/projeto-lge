<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutividadesTable extends Migration
{
    public function up()
    {
        Schema::create('produtividades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('linha', 50);
            $table->date('data_producao');
            $table->unsignedInteger('quantidade_produzida');
            $table->unsignedInteger('quantidade_defeitos');

            $table->index(['data_producao', 'linha']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('produtividades');
    }
}
