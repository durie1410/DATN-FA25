<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserInfoFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'province')) {
                $table->string('province')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'district')) {
                $table->string('district')->nullable()->after('province');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('district');
            }
            if (!Schema::hasColumn('users', 'so_cccd')) {
                $table->string('so_cccd', 20)->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'province', 'district', 'address', 'so_cccd']);
        });
    }
}
