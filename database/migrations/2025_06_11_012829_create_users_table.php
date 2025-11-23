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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('phone_number', 100)->unique();
            $table->string('image', 50)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('active')->default(0)->comment('1=active,0=nonactive');
            $table->string('activation_code', 50)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
