<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('access_records', function (Blueprint $table) {
            $table->bigInteger('device_id');
            $table->bigInteger('ip_id');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('resource')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_records');
    }
}
