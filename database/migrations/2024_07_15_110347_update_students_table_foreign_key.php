<?php

// database/migrations/xxxx_xx_xx_update_students_table_foreign_key.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableForeignKey extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->foreign('class_id')->references('id')->on('classrooms');
        });
    }
}
