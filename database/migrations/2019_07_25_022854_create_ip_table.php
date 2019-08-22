<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpTable extends Migration
{
  public function up()
  {
    Schema::create('ip', function (Blueprint $table) {
      $table->increments('id');
      $table->string('address');
      $table->string('province');
      $table->string('city');
      $table->timestamp('updated_at');
    });
  }

  public function down()
  {
    Schema::dropIfExists('ip');
  }
}
