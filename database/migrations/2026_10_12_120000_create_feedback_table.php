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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('learning_resource_id')->nullable()->constrained('resources')->nullOnDelete();
            $table->string('category'); // content, ux, payment, support
            $table->unsignedTinyInteger('rating')->nullable(); // 1 to 5
            $table->text('message');
            $table->string('url')->nullable();
            $table->string('status')->default('new'); // new, reviewed, resolved
            $table->text('admin_response')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
