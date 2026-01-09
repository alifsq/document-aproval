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
        Schema::create('document_aprovals', function (Blueprint $table) {
            $table->id();
            $table->string('decission', 50);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreignId('document_id')->constrained('users');
            $table->foreignId('approved_id')->constrained('documents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_aprovals');
    }
};
