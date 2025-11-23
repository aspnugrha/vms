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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->string('lokasi', 100)->nullable();
            $table->string('pavicon', 100)->nullable();
            $table->string('logo', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('email', 100);
            $table->string('phone_number', 30);
            $table->string('whatsapp', 30)->nullable();
            $table->string('telegram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->text('maps')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('updated_by', 50)->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
