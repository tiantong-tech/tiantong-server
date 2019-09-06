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
      $table->string('type')->default('sale');
      $table->string('sale_type')->default('hoister');
      $table->string('progress')->default('交流中');

      $table->string('name')->default('');
      $table->string('company')->default('');
      $table->text('customer_information')->default('');
      $table->text('notes')->default('');

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
