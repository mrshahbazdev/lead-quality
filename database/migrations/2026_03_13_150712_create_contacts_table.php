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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->enum('status', ['new', 'active', 'inactive', 'hot', 'cold'])->default('new');
            $table->string('industry')->nullable();
            $table->string('role')->nullable();
            $table->string('source')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('budget_range')->nullable();
            $table->string('employee_count_range')->nullable();
            $table->integer('priority')->default(1);
            $table->timestamp('last_interaction_at')->nullable();
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
