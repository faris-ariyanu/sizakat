<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationToTrxZakatItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_zakat_items', function (Blueprint $table) {
            $table->string("muzakki_ktp")->nullable(true);
            $table->string("muzakki_relation")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_zakat_items', function (Blueprint $table) {
            $table->dropColumn("muzakki_ktp");
            $table->dropColumn("muzakki_relation");
        });
    }
}
