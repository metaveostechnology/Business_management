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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            // Company & Branch ownership
            $table->foreignId('company_id')
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->onDelete('set null');

            // Unique slug
            $table->string('slug', 150)->unique();

            // Hierarchy — self references (added after table creation via foreign keys)
            $table->unsignedBigInteger('parent_department_id')->nullable();
            $table->unsignedBigInteger('reports_to_department_id')->nullable();

            // Identity
            $table->string('code', 50);
            $table->string('name', 150);
            $table->text('description')->nullable();

            // Department head
            $table->unsignedBigInteger('head_user_id')->nullable();

            // Hierarchy metadata
            $table->integer('level_no')->default(1);

            // Workflow configuration
            $table->enum('approval_mode', ['single', 'multi', 'hierarchical'])->default('hierarchical');
            $table->enum('escalation_mode', ['none', 'manager_to_ceo', 'full_chain', 'custom'])->default('full_chain');

            // Permissions
            $table->boolean('can_create_tasks')->default(true);
            $table->boolean('can_receive_tasks')->default(true);

            // System flags
            $table->boolean('is_system_default')->default(false);
            $table->boolean('is_active')->default(true);

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();

            // Constraints
            $table->unique(['company_id', 'code'], 'departments_company_code_unique');
        });

        // Self-referencing foreign keys added after table creation
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('parent_department_id')
                ->references('id')->on('departments')
                ->onDelete('set null');

            $table->foreign('reports_to_department_id')
                ->references('id')->on('departments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['parent_department_id']);
            $table->dropForeign(['reports_to_department_id']);
        });

        Schema::dropIfExists('departments');
    }
};
