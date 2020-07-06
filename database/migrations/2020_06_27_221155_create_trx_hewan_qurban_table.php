<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrxHewanQurbanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_hewan_qurban', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('hewan_qurban_id');
			$table->integer("periode");
			$table->string('no_urut');

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
        Schema::dropIfExists('trx_hewan_qurban');
    }
}
