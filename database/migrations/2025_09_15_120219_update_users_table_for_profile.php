<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar_path')) {
                $table->string('avatar_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'avatar_base64')) {
                $table->longText('avatar_base64')->nullable();
            }

            // Notifications
            if (!Schema::hasColumn('users', 'notifications_enabled')) {
                $table->boolean('notifications_enabled')->default(true);
            }
            if (!Schema::hasColumn('users', 'ch_task_reminders')) {
                $table->boolean('ch_task_reminders')->default(true);
            }
            if (!Schema::hasColumn('users', 'ch_notes_updates')) {
                $table->boolean('ch_notes_updates')->default(true);
            }
            if (!Schema::hasColumn('users', 'ch_product_news')) {
                $table->boolean('ch_product_news')->default(true);
            }

            // DND
            if (!Schema::hasColumn('users', 'dnd_enabled')) {
                $table->boolean('dnd_enabled')->default(false);
            }
            if (!Schema::hasColumn('users', 'dnd_start_hour')) {
                $table->tinyInteger('dnd_start_hour')->default(22);
            }
            if (!Schema::hasColumn('users', 'dnd_end_hour')) {
                $table->tinyInteger('dnd_end_hour')->default(7);
            }
        });
    }
    public function down(): void
    {
        // keep it simple for dev; usually you drop the added columns here
    }
};
