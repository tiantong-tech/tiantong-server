<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
  public function up()
  {
    Schema::create('quotations', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('project_id');
      $table->bigInteger('design_schema_id');

      $table->string('type');
      $table->string('name');
      $table->text('content');

      $table->timestamp('deadline')->nullable();
      $table->timestamp('offered_at')->nullable();
      $table->timestamp('created_at');
    });
  }

  public function down()
  {
    Schema::dropIfExists('quotations');
  }
}
