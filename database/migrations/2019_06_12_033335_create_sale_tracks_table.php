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
            $table->timestamp('created')->useCurrent();
            $table->enum('status', Enums::saleTrackStatuses)->default('确认中');

            $table->enum('type', Enums::saleTrackTypes);
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('message')->nullable();
            $table->jsonb('data')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_tracks');
    }
}
