<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->unique();         // Google ID for the customer
            $table->string('google_access_token')->nullable(); // Store Google access token
            $table->string('google_user_name')->nullable(); // Store Google user name
            $table->string('email')->nullable(); // Store the email, if needed
            $table->timestamps(); // Automatically handle created_at and updated_at
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('customers');
    }
    
};
