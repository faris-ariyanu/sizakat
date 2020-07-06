<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrxZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_zakat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("transaction_number")->unique();
            $table->integer("periode");
            $table->string("name");
            $table->string("address")->nullable(true);
            $table->string("phone")->nullable(true);
            $table->string("email")->nullable(true);
            $table->integer("created_by");
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
        Schema::dropIfExists('trx_zakat');
    }
}
