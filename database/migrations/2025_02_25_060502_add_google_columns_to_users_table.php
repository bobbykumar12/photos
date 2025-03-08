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
        Schema::table('users', function (Blueprint $table) {
            // Adding new columns for Google authentication
            $table->string('google_id')->unique();         // Google ID for the user
            $table->string('google_access_token')->nullable(); // Store Google access token
            $table->string('google_user_name')->nullable(); // Store Google user name
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping the columns if the migration is rolled back
            $table->dropColumn('google_id');
            $table->dropColumn('google_access_token');
            $table->dropColumn('google_user_name');
        });
    }
    
};
