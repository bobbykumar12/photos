<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('google_photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo_id')->unique();
            $table->text('photo_url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_photos');
    }
};

