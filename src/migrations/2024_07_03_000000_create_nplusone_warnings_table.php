<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNplusoneWarningsTable extends Migration
{
    public function up()
    {
        Schema::create('nplusone_warnings', function (Blueprint $table) {
            $table->id();
            $table->text('sql')->nullable();
            $table->text('location')->nullable();
            $table->text('solution')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nplusone_warnings');
    }
}