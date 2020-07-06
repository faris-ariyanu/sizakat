<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodIdToTrxZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_zakat', function (Blueprint $table) {
            $table->integer("payment_method_id")->default(0);
            $table->string("payment_method_name")->nullable(true);
            $table->integer("status")->default(0);
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
            $table->dropColumn("payment_method_id");
            $table->dropColumn("payment_method_name");
            $table->dropColumn("status");
        });
    }
}
