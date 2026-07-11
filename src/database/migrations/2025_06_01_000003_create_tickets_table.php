<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->onDelete('set null');
            $table->enum('category', ['TV', 'HP', 'LAPTOP']);
            $table->string('brand')->nullable();
            $table->json('photo_urls')->nullable();
            $table->text('description');
            $table->date('preferred_date');
            $table->string('preferred_time');
            $table->enum('status', [
                'PENDING',
                'REJECTED',
                'WAITING_ASSIGNMENT',
                'ASSIGNED',
                'ON_THE_WAY',
                'DIAGNOSIS',
                'WAITING_PART',
                'REPAIR',
                'COMPLETED',
                'CLOSED',
            ])->default('PENDING');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
