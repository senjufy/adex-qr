<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveSopNumberToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('sop_number')->nullable()->after('name');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('sop_number');
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
            $table->string('sop_number')->nullable()->after('title');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('sop_number');
        });
    }
}
