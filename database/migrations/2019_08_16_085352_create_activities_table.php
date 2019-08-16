<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
  public function up()
  {
    Schema::create('activities', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('type');
      $table->string('name');
      $table->text('notes');
      $table->timestamp('created_at');
      $table->timestamp('updated_at');
    });

    DB::statement("ALTER TABLE activities ADD COLUMN user_ids integer[] DEFAULT '{}'");
  }

  public function down()
  {
    Schema::dropIfExists('activities');
  }
}
