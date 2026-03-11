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

            // Ticket identity
            $table->string('ticket_number')->unique();
            $table->string('title');
            $table->text('description');

            // Category of issue
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Priority & status
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])
                ->default('medium');

            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])
                ->default('open');

            // User who created the ticket
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Technician assigned to ticket
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Department where issue originated
            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Ticket source information
            $table->enum('site_type', ['hq', 'branch'])->nullable();

            $table->string('source_name')
                ->nullable(); // e.g. Reception, ICT Manager, Embu, Nairobi

            $table->string('extension_number', 10)
                ->nullable(); // e.g. 700, 726, 803

            // Technician notes when resolving ticket
            $table->text('resolution_notes')->nullable();

            // Important for technician performance metrics
            $table->timestamp('resolved_at')->nullable();

            // Laravel timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};