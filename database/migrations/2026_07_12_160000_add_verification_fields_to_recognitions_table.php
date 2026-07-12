<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recognitions', function (Blueprint $table) {
            $table->string('batch_id')->nullable()->after('document_type');
            $table->json('extracted_fields')->nullable()->after('batch_id');
            $table->json('corrected_fields')->nullable()->after('extracted_fields');
            $table->unsignedBigInteger('verified_by')->nullable()->after('corrected_fields');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('rejection_reason')->nullable()->after('verified_at');
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('recognitions', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['batch_id', 'extracted_fields', 'corrected_fields', 'verified_by', 'verified_at', 'rejection_reason']);
        });
    }
};
