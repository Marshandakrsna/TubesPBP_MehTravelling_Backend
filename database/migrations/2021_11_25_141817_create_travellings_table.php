<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateTravellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travellings', function (Blueprint $table) {
            $table->id();
            $table->string('namaDestinasi'); //tipedata varchar 255
            $table->string('namaPengguna'); //tipedata varchar 255
            $table->string('penilaian');
            $table->string('alasan');
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
        Schema::dropIfExists('travellings');
    }
}
