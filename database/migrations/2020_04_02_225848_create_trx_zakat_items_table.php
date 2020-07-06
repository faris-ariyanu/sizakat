<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrxZakatItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_zakat_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("transaction_id");
            $table->string("transaction_sub_id");
            $table->integer("muzakki_id");
            $table->string("muzakki_name")->nullable(true);
            $table->string("muzakki_phone")->nullable(true);
            $table->string("muzakki_address")->nullable(true);
            $table->string("muzakki_email")->nullable(true);
            $table->string("jenis_fitrah")->nullable(true);
            $table->string("zakat_type_id");
            $table->integer("zakat_quality_id");
            $table->string("transaction_type");
            $table->double("income_value")->default(0);
            $table->double("income_goods")->default(0);
            $table->integer("status")->default(0);
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
        Schema::dropIfExists('trx_zakat_items');
    }
}
