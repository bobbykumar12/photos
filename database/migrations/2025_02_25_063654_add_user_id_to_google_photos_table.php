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
        Schema::table('google_photos', function (Blueprint $table) {
            // Add the user_id column
            $table->unsignedBigInteger('user_id')->nullable()->after('photo_url');
            
            // Optional: Add foreign key constraint if you want to enforce integrity
            $table->foreign('user_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('google_photos', function (Blueprint $table) {
            // Drop the user_id column if the migration is rolled back
            $table->dropColumn('user_id');
        });
    }
    
};
