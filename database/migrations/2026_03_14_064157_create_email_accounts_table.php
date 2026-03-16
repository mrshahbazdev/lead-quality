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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            
            $table->string('email_address');
            $table->string('provider')->default('custom'); // gmail, outlook, custom
            
            // IMAP
            $table->string('imap_host');
            $table->integer('imap_port')->default(993);
            $table->string('imap_encryption')->default('ssl');
            
            // SMTP
            $table->string('smtp_host');
            $table->integer('smtp_port')->default(465);
            $table->string('smtp_encryption')->default('ssl');
            
            // Credentials
            $table->string('username');
            $table->text('password'); // Will be encrypted via Eloquent Casting
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
