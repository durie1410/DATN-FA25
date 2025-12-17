<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFacultyDepartmentToReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('readers', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['faculty_id', 'department_id']);
        });
    }
}
