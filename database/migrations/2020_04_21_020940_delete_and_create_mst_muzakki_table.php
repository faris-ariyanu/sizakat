<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAndCreateMstMuzakkiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mst_muzakki');
        Schema::create('mst_muzakki', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_ktp')->unique();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('email')->nullable(true);
            $table->text('address')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('photo')->nullable(true);
            $table->timestamp('registered_date');
            $table->integer('parent')->default(0);
            $table->string('relation')->nullable(true);
            $table->string('muzakki_status')->default("activated");
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
        Schema::dropIfExists('mst_muzakki');
        Schema::create('mst_muzakki', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('id_card')->unique();
            $table->string('name');
            $table->string('email')->nullable(true);
            $table->text('address')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('avatar')->nullable(true);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }
}
