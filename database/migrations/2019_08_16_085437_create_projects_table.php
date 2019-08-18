<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
  public function up()
  {
    Schema::create('projects', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('type');

      $table->string('name');
      $table->string('company');
      $table->text('customer_information');
      $table->text('notes');

      $table->timestamp('delivery_date')->nullable();
      $table->timestamp('signature_date')->nullable();
      $table->timestamp('created_at');
    });

    DB::statement("ALTER TABLE projects ADD COLUMN status integer[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN file_ids integer[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN activity_ids integer[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN member_ids integer[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN design_schema_ids integer[] DEFAULT '{}'");
  }

  public function down()
  {
    Schema::dropIfExists('projects');
  }
}
