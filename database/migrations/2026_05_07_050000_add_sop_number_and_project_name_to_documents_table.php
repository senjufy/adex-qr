<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSopNumberAndProjectNameToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'sop_number')) {
                $table->string('sop_number')->nullable()->after('title');
            }

            if (!Schema::hasColumn('documents', 'project_name')) {
                $table->string('project_name')->nullable()->after('sop_number');
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
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'sop_number')) {
                $table->dropColumn('sop_number');
            }

            if (Schema::hasColumn('documents', 'project_name')) {
                $table->dropColumn('project_name');
            }
        });
    }
}
