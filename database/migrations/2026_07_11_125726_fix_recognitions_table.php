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
        Schema::dropIfExists('recognitions');
        Schema::create('recognitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_type')->default('image'); // image, pdf, word
            $table->string('status')->default('pending');
            $table->text('recognized_text')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->json('api_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recognitions');
        Schema::create('recognitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('original_filename');
            $table->string('status')->default('pending');
            $table->text('recognized_text')->nullable();
            $table->json('api_response')->nullable();
            $table->timestamps();
        });
    }
};
