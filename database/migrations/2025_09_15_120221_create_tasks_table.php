<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->time('time');
            $table->time('end_time')->nullable();
            $table->decimal('progress', 4, 2)->default(0);
            $table->enum('status', ['todo', 'progress', 'done', 'inProgress'])->default('todo');
            $table->unsignedBigInteger('gradient_start')->nullable();
            $table->unsignedBigInteger('gradient_end')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
