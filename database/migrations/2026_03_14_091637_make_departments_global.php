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
        Schema::table('departments', function (Blueprint $table) {
            // Drop foreign keys FIRST
            $table->dropForeign(['company_id']);
            $table->dropForeign(['branch_id']);
            
            // Drop the old unique constraint
            $table->dropUnique('departments_company_code_unique');
            
            // Drop columns
            $table->dropColumn(['company_id', 'branch_id']);

            // Make code unique globally
            $table->unique('code', 'departments_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropUnique('departments_code_unique');

            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');

            $table->unique(['company_id', 'code'], 'departments_company_code_unique');
        });
    }
};
