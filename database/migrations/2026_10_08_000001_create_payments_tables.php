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
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->string('provider')->default('razorpay');
                $table->string('provider_order_id')->unique();
                $table->string('provider_payment_id')->nullable()->unique();
                $table->unsignedInteger('amount');
                $table->char('currency', 3)->default('INR');
                $table->string('status')->default('created');
                $table->string('method')->nullable();
                $table->text('failure_reason')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->text('payload_reference')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'status']);
            });
        }

        if (! Schema::hasTable('refunds')) {
            Schema::create('refunds', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('amount');
                $table->string('status')->default('pending');
                $table->string('provider_refund_id')->nullable()->unique();
                $table->text('reason')->nullable();
                $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('webhook_events')) {
            Schema::create('webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('provider')->default('razorpay');
                $table->string('event_id');
                $table->string('event_type');
                $table->boolean('signature_valid')->default(true);
                $table->text('payload');
                $table->timestamp('received_at');
                $table->timestamp('processed_at')->nullable();
                $table->string('status')->default('received');
                $table->text('error')->nullable();
                $table->unsignedSmallInteger('attempts')->default(1);
                $table->timestamps();

                $table->unique(['provider', 'event_id']);
                $table->index(['status', 'received_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
    }
};
