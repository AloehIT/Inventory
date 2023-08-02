<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id();
            $table->string('users_id')->unique();
            $table->string('nik')->unique();
            $table->string('nama_users')->nullable();
            $table->string('telpon')->nullable();
            $table->string('email')->nullable();
            $table->string('profile')->nullable();
            $table->string('ktp')->nullable();
            $table->string('perushaanid')->unique();
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
        Schema::dropIfExists('detail_users');
    }
}
