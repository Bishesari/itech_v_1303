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
        Schema::create('permissions', function (Blueprint $table) {

            $table->id();

            $table->string('slug', 100)->unique();

            $table->string('name', 100);

            $table->string('group', 50)
                ->nullable()
                ->index();

            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
