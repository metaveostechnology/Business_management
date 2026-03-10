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
        Schema::create('department_features', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->constrained('departments')
                ->onDelete('cascade');

            $table->foreignId('feature_id')
                ->constrained('features')
                ->onDelete('cascade');

            $table->string('slug', 150)->unique();

            $table->enum('access_level', ['view', 'create', 'edit', 'delete', 'approve', 'full'])
                ->default('view');

            $table->boolean('is_enabled')->default(true);

            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->useCurrent();

            $table->timestamps();

            // A feature can only be assigned once per department
            $table->unique(['department_id', 'feature_id'], 'dept_feature_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_features');
    }
};
