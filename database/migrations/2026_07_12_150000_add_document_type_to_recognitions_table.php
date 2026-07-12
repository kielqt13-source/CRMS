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
        Schema::table('recognitions', function (Blueprint $table) {
            if (!Schema::hasColumn('recognitions', 'document_type')) {
                $table->string('document_type')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recognitions', function (Blueprint $table) {
            if (Schema::hasColumn('recognitions', 'document_type')) {
                $table->dropColumn('document_type');
            }
        });
    }
};
