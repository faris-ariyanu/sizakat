<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstKualitasZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_kualitas_zakat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("type_zakat");
            $table->string("description")->nullable(true);
            $table->integer("value")->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_kualitas_zakat');
    }
}
