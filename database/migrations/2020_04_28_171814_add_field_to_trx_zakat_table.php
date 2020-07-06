<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToTrxZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_zakat', function (Blueprint $table) {
            $table->string("receipt")->nullable(true);
            $table->string("sender_bank")->nullable(true);
            $table->string("sender_name")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_zakat', function (Blueprint $table) {
            $table->dropColumn("receipt");
            $table->dropColumn("sender_bank");
            $table->dropColumn("sender_name");
        });
    }
}
