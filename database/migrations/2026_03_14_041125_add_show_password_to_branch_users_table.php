<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('branch_users', function (Blueprint $table) {
            $table->string('show_password')->nullable()->after('password')->comment('Plain-text password for admin/branch view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_users', function (Blueprint $table) {
            $table->dropColumn('show_password');
        });
    }
};
