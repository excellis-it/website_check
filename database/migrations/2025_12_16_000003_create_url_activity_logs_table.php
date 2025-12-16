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
        Schema::create('url_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('url_id')->constrained('url_management')->onDelete('cascade');
            $table->enum('status', ['up', 'down'])->default('up');
            $table->integer('response_time')->nullable()->comment('Response time in milliseconds');
            $table->integer('status_code')->nullable()->comment('HTTP status code');
            $table->text('error_message')->nullable();
            $table->timestamp('checked_at');
            $table->date('log_date')->index()->comment('Date for daily grouping');
            $table->timestamps();

            // Index for faster queries
            $table->index(['url_id', 'log_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_activity_logs');
    }
};
