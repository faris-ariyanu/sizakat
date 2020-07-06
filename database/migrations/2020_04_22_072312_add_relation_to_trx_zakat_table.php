<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationToTrxZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_zakat', function (Blueprint $table) {
            $table->string("no_ktp")->nullable(true);
            $table->string("relation")->nullable(true);
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
            $table->dropColumn("no_ktp");
            $table->dropColumn("relation");
        });
    }
}
