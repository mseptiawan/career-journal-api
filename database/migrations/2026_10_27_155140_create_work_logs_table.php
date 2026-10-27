<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category'); // coding, meeting, debugging, deployment, research, etc.
            $table->dateTime('worked_at');
            $table->integer('duration_minutes')->nullable();
            $table->text('outcome')->nullable();
            $table->text('blockers')->nullable();
            $table->text('solution')->nullable();
            $table->jsonb('technologies')->nullable();
            $table->string('status')->default('completed'); // completed, in_progress, pending
            $table->jsonb('links')->nullable(); // Array of PR links, docs, etc.
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};
