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
            // Add the user_name column
            $table->string('user_name')->nullable()->after('photo_url');
        });
    }
    
    public function down()
    {
        Schema::table('google_photos', function (Blueprint $table) {
            // Drop the user_name column if the migration is rolled back
            $table->dropColumn('user_name');
        });
    }
    
};
