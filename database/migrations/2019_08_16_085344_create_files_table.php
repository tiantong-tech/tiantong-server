<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
  public function up()
  {
    Schema::create('files', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('user_id');
      $table->string('namespace');
      $table->string('name');
      $table->string('link');
      $table->string('status');
      $table->timestamp('created_at');
      $table->timestamp('deleted_at')->nullable();
    });
  }

  public function down()
  {
    Schema::dropIfExists('files');
  }
}
