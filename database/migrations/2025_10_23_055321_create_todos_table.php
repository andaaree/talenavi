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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('assignee')->nullable();
            $table->date('due_date');
            $table->unsignedInteger('time_tracked',false)->default(0);
            $table->enum('status',['pending', 'open', 'in_progress', 'completed'])->default('pending');
            $table->enum('priority',['low', 'medium', 'high'])->default('low');
            $table->timestamps();
            $table->softDeletes('deleted_at',0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
