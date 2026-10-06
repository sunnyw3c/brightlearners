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
        Schema::create('learning_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nickname', 40);
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('avatar_key')->nullable();
            $table->json('interests')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_profiles');
    }
};
