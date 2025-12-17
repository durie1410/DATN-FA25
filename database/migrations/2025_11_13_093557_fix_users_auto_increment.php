<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixUsersAutoIncrement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get the maximum ID from users table
        $maxId = DB::table('users')->max('id');
        
        // Set AUTO_INCREMENT to max ID + 1, or 1 if table is empty
        $newAutoIncrement = $maxId ? $maxId + 1 : 1;
        
        // Fix auto increment
        DB::statement("ALTER TABLE users AUTO_INCREMENT = {$newAutoIncrement}");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to reverse this migration
    }
}
