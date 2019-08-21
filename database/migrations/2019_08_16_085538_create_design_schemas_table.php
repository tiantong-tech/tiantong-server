<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignSchemasTable extends Migration
{
  public function up()
  {
    Schema::create('design_schemas', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('project_id');

      $table->string('type');
      $table->string('name');
      $table->string('data');
      $table->string('notes');
      $table->string('quantity');

      $table->boolean('is_completed')->default(false);
      $table->timestamp('created_at');
    });

    DB::statement("ALTER TABLE design_schemas ADD COLUMN quotation_ids bigInt[] DEFAULT '{}'");
  }

  public function down()
  {
    Schema::dropIfExists('design_schemas');
  }
}
