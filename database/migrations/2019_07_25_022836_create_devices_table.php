<?php

use App\Facades\Series;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
  public function up()
  {
    Schema::create('devices', function (Blueprint $table) {
      $table->increments('id');
      $table->string('key')->nullable()->unique();
      $table->string('user_agent');
      $table->timestamp('created_at');
    });
  }

  public function down()
  {
    Schema::dropIfExists('devices');
    Series::delete('device_id');
  }
}
