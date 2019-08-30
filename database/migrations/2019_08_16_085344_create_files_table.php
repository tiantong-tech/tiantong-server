<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
  public function up()
  {
    Schema::create('files', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('user_id');
      $table->string('namespace');
      $table->string('name');
      $table->string('comment')->default('');
      $table->integer('size')->default(0);
      $table->string('link')->default('');
      $table->boolean('is_uploaded')->default(false);
      $table->timestamp('created_at');
      $table->timestamp('deleted_at')->nullable();
    });
  }

  public function down()
  {
    Schema::dropIfExists('files');
  }
}
