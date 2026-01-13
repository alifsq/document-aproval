<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->timestamp('submitted_at');
            $table->timestamp('approved_at');
            $table->timestamp('rejected_at');
            $table->timestamps();

            $table->foreignId('tenant_id')->constrained();
            $table->foreignId('created_by')->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
