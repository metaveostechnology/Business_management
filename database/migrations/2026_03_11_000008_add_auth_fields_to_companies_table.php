<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add authentication fields to the companies table.
     * This moves company login credentials from company_register to companies.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Authentication credentials
            $table->string('password', 255)->nullable()->after('email');

            // Simplified address field (in addition to existing address_line1 etc.)
            $table->text('address')->nullable()->after('postal_code');

            // Soft-delete flag
            $table->boolean('is_delete')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['password', 'address', 'is_delete']);
        });
    }
};
