<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlSendNotifToTrxZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_zakat', function (Blueprint $table) {
            $table->integer("fl_send_notif")->default(0);
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
            $table->dropColumn("fl_send_notif");
        });
    }
}
