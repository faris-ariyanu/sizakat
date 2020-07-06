<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalToTrxZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_zakat', function (Blueprint $table) {
            $table->double("total_money")->default(0);
            $table->double("total_goods")->default(0);
            $table->integer("unique_code")->default(0);
            $table->timestamp("expire_time")->nullable(true);
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
            $table->dropColumn("total_money");
            $table->dropColumn("total_goods");
            $table->dropColumn("unique_code");
            $table->dropColumn("expire_time");
        });
    }
}
