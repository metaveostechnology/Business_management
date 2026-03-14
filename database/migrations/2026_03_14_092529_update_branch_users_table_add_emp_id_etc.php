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
            // Drop role_id (assume it has a foreign key)
            if (Schema::hasColumn('branch_users', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }

            // Add new columns conditionally DDL
            if (!Schema::hasColumn('branch_users', 'emp_id')) {
                $table->string('emp_id', 20)->unique()->after('company_id');
            }
            if (!Schema::hasColumn('branch_users', 'dept_id')) {
                $table->foreignId('dept_id')->nullable()->after('branch_id')->constrained('departments')->nullOnDelete();
            }
            if (!Schema::hasColumn('branch_users', 'is_dept_admin')) {
                $table->boolean('is_dept_admin')->default(0)->after('phone');
            }
            if (!Schema::hasColumn('branch_users', 'is_branch_admin')) {
                $table->boolean('is_branch_admin')->default(0)->after('is_dept_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_users', function (Blueprint $table) {
            $table->dropForeign(['dept_id']);
            $table->dropColumn(['emp_id', 'dept_id', 'is_dept_admin', 'is_branch_admin']);
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
        });
    }
};
