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
        Schema::create('role_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institute_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['user_id', 'role_id', 'institute_id', 'branch_id',], 'unique_role_assignment');
            
            $table->index('user_id');
            $table->index('role_id');
            $table->index(['institute_id', 'branch_id']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_assignments');
    }
};
