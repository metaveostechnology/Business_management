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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->onDelete('cascade');

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->onDelete('cascade');

            $table->string('slug', 150)->unique();

            $table->string('setting_group', 80);
            $table->string('setting_key', 100);

            $table->longText('setting_value')->nullable();

            $table->enum('value_type', ['string', 'integer', 'float', 'boolean', 'json', 'text'])
                ->default('string');

            $table->boolean('is_public')->default(false);

            $table->timestamps();

            // Unique combination per company + branch + group + key
            $table->unique(
            ['company_id', 'branch_id', 'setting_group', 'setting_key'],
                'system_settings_scope_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
