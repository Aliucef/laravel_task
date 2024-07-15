<?php

// database/migrations/xxxx_xx_xx_add_foreign_key_to_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            $table->dropForeign(['class_id']); // Drop by column name, not constraint name

            // Add foreign key with cascade on delete
            $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the foreign key added in the up() method
            $table->dropForeign(['class_id']);

            // Optionally, re-add the original foreign key (without cascade)
            // $table->foreign('class_id')->references('id')->on('classrooms');
        });
    }
}
