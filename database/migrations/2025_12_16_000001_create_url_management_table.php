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
        Schema::create('url_management', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url');
            $table->enum('status', ['active', 'inactive', 'down'])->default('inactive');
            $table->timestamp('last_checked_at')->nullable();
            $table->integer('response_time')->nullable()->comment('Response time in milliseconds');
            $table->integer('status_code')->nullable()->comment('HTTP status code');
            $table->text('error_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_management');
    }
};
