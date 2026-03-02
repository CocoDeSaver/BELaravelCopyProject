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
        Schema::create('ticket_sessions', function (Blueprint $table) {
            $table->id();
            $table->ForeignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('remaining_messages');
            $table->integer('total_messages')->default(20);
            $table->enum('status', ['active', 'finished', 'expired'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_sessions');
    }
};
