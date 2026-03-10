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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            // Company ownership
            $table->foreignId('company_id')
                ->constrained('companies')
                ->onDelete('cascade');

            // Branch identity
            $table->string('code', 50);
            $table->string('name', 150);
            $table->string('slug', 180)->unique();

            // Contact info
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();

            // Manager
            $table->unsignedBigInteger('manager_user_id')->nullable();

            // Address
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('postal_code', 30)->nullable();

            // Location
            $table->text('google_map_link')->nullable();

            // System flags
            $table->boolean('is_head_office')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Constraints
            $table->unique(['company_id', 'code'], 'branches_company_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
