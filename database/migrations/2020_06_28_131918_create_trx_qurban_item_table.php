<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrxQurbanItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_qurban_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('trx_qurban_id');
            $table->string('name');
            $table->boolean('status_cacahan');
            $table->boolean('cacahan_diambil')->default(false);

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
        Schema::dropIfExists('trx_qurban_item');
    }
}
