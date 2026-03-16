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
            if (Schema::hasColumn('branch_users', 'show_password')) {
                $table->dropColumn('show_password');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_users', function (Blueprint $table) {
            $table->string('show_password')->nullable()->after('password');
        });
    }
};
