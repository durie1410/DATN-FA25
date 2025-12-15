<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersRoleEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cập nhật các giá trị role hiện tại để phù hợp với enum mới
        DB::table('users')->where('role', 'admin')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'user')->update(['role' => 'user']);
        
        // Thêm constraint check để đảm bảo chỉ có các giá trị hợp lệ
        DB::statement("ALTER TABLE users ADD CONSTRAINT check_role CHECK (role IN ('admin', 'staff', 'user'))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Xóa constraint
        DB::statement("ALTER TABLE users DROP CONSTRAINT check_role");
    }
}