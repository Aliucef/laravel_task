<?php

// database/migrations/xxxx_xx_xx_add_foreign_key_raw_to_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddForeignKeyRawToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            DB::statement('ALTER TABLE students DROP FOREIGN KEY IF EXISTS students_class_id_foreign;');

            // Add foreign key with cascade on delete
            DB::statement('ALTER TABLE students ADD CONSTRAINT students_class_id_foreign FOREIGN KEY (class_id) REFERENCES classrooms(id) ON DELETE CASCADE;');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the foreign key added in the up() method
            DB::statement('ALTER TABLE students DROP FOREIGN KEY students_class_id_foreign;');
        });
    }
}
