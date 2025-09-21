<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->default('');
            $table->text('content')->default('');
            $table->foreignId('folder_id')->nullable()->constrained('folders')->nullOnDelete();
            $table->unsignedBigInteger('accent_argb')->default(0xFF94A3B8);
            $table->longText('rich_json')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
