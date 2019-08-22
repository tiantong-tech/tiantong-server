<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactDeviceIpRecordsBigint extends Migration
{
  public function up()
  {
    Schema::table('devices', function ($table) {
      $table->increments('id')->change();
    });

    Schema::table('ip', function ($table) {
      $table->increments('id')->change();
    });

    Schema::table('access_records', function ($table) {
      $table->increments('id');
      $table->integer('ip_id')->change();
      $table->integer('device_id')->change();
    });
  }

  public function down()
  {

  }
}
