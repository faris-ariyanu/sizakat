<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAndCreateMstMustahikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('mst_mustahik');

        Schema::create('mst_mustahik', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_ktp')->unique();
            $table->string('name');
            $table->text('address')->nullable(true);
            $table->string('province')->nullable(true);
            $table->string('regency')->nullable(true);
            $table->string('rt')->nullable(true);
            $table->string('rw')->nullable(true);
            $table->date('birthdate')->nullable(true);
            $table->integer('age')->default(0);
            $table->string('mustahik_status')->nullable(true);
            $table->integer('family_size')->default(0);
            $table->string('photo')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->text('description')->nullable(true);
            $table->integer('category_id');
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
        Schema::dropIfExists('mst_mustahik');
        Schema::create('mst_mustahik', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('id_card')->unique();
            $table->string('name');
            $table->text('address')->nullable(true);
            $table->string('province')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('rt')->nullable(true);
            $table->string('rw')->nullable(true);
            $table->date('dob')->nullable(true);
            $table->integer('age')->default(0);
            $table->string('status_mustahik')->nullable(true);
            $table->integer('total_family')->default(0);
            $table->string('avatar')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->text('description')->nullable(true);
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }
}
