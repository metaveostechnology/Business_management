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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 180)->unique();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->string('legal_name', 200)->nullable();
            $table->string('email', 150)->nullable()->unique();
            $table->string('phone', 10)->nullable();
            $table->string('website', 200)->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('registration_number', 100)->nullable();
            $table->string('currency_code', 10)->default('INR');
            $table->string('timezone', 100)->default('Asia/Kolkata');
            $table->string('address_line1', 255)->nullable();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('country', 120)->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
