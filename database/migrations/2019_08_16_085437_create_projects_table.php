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
      $table->string('sale_type')->nullable();
      $table->string('progress');

      $table->string('name');
      $table->string('company');
      $table->text('customer_information');
      $table->text('notes');

      $table->timestamp('delivery_date')->nullable();
      $table->timestamp('signature_date')->nullable();
      $table->timestamp('created_at');
    });

    DB::statement("ALTER TABLE projects ADD COLUMN status bigInt[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN file_ids bigInt[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN activity_ids bigInt[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN member_ids bigInt[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ADD COLUMN design_schema_ids bigInt[] DEFAULT '{}'");
    DB::statement("ALTER TABLE projects ALTER COLUMN file_ids TYPE int[]");
  }

  public function down()
  {
    Schema::dropIfExists('projects');
  }
}
