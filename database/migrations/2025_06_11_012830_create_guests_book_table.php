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
        Schema::create('guests_book', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('visitor_id', 50);
            $table->string('visitor_name', 100);
            $table->string('visitor_email', 100)->nullable();
            $table->string('visitor_phone_number', 100)->nullable();
            $table->string('visit_type', 100);
            $table->dateTime('checkin_time')->nullable();
            $table->dateTime('checkout_time')->nullable();
            $table->double('visit_time_total')->nullable();
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
        Schema::dropIfExists('guests_book');
    }
};
