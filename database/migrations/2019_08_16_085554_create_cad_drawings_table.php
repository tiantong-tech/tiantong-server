<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCadDrawingsTable extends Migration
{
  public function up()
  {
    Schema::create('cad_drawings', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('project_id');
      $table->bigInteger('design_schema_id');

      $table->timestamp('deadline')->nullable();
      $table->timestamp('offered_at')->nullable();
      $table->timestamp('created_at');

      $table->unique(['id', 'design_schema_id']);
    });
  }

  public function down()
  {
    Schema::dropIfExists('cad_drawings');
  }
}
