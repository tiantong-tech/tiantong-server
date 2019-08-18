<?php

use App\Services\Enums;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTracksTable extends Migration
{
    public function up()
    {
        Schema::create('sale_tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_at')->useCurrent();
            $table->string('status')->default('确认中');
            $table->string('type');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('message')->nullable();
            $table->string('company')->nullable();
            $table->string('project_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->jsonb('data')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_tracks');
    }
}
