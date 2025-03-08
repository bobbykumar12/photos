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
        Schema::table('customers', function (Blueprint $table) {
            // Add the remember_token column
            $table->string('remember_token', 100)->nullable()->after('email');
        });
    }
    
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop the remember_token column if the migration is rolled back
            $table->dropColumn('remember_token');
        });
    }
    
};
