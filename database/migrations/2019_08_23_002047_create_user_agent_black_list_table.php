<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAgentBlackListTable extends Migration
{
  public function up()
  {
    Schema::create('device_blacklist', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('user_agent')->unique();
      $table->boolean('is_confirmed')->default(false);
      $table->timestamp('created_at');
    });
  }

  public function down()
  {
    Schema::dropIfExists('device_blacklist');
  }
}
