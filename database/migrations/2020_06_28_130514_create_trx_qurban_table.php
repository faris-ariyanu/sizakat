<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrxQurbanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_qurban', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("periode");
            $table->integer('trx_hewan_qurban_id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
			$table->double('income_value')->nullable();
            $table->double('income_goods')->nullable();
            $table->integer('status');

            $table->integer('created_by');
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
        Schema::dropIfExists('trx_qurban');
    }
}
