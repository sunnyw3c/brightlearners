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
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (! Schema::hasColumn('subscriptions', 'provider_subscription_id')) {
                    $table->string('provider_subscription_id')->nullable()->unique()->after('auto_renew');
                }
                if (! Schema::hasColumn('subscriptions', 'provider')) {
                    $table->string('provider')->default('razorpay')->after('provider_subscription_id');
                }
                if (! Schema::hasColumn('subscriptions', 'current_term_starts_at')) {
                    $table->timestamp('current_term_starts_at')->nullable()->after('provider');
                }
                if (! Schema::hasColumn('subscriptions', 'current_term_ends_at')) {
                    $table->timestamp('current_term_ends_at')->nullable()->after('current_term_starts_at');
                }
                if (! Schema::hasColumn('subscriptions', 'grace_ends_at')) {
                    $table->timestamp('grace_ends_at')->nullable()->after('current_term_ends_at');
                }
                if (! Schema::hasColumn('subscriptions', 'cancel_at_period_end')) {
                    $table->boolean('cancel_at_period_end')->default(false)->after('grace_ends_at');
                }
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->unsignedBigInteger('order_id')->nullable()->change();

                if (! Schema::hasColumn('payments', 'subscription_id')) {
                    $table->foreignId('subscription_id')->nullable()->after('order_id')->constrained('subscriptions')->nullOnDelete();
                }
                if (! Schema::hasColumn('payments', 'is_renewal')) {
                    $table->boolean('is_renewal')->default(false)->after('subscription_id');
                }
                if (! Schema::hasColumn('payments', 'renewal_reference')) {
                    $table->string('renewal_reference')->nullable()->after('is_renewal');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'subscription_id')) {
                    $table->dropForeign(['subscription_id']);
                    $table->dropColumn('subscription_id');
                }
                if (Schema::hasColumn('payments', 'is_renewal')) {
                    $table->dropColumn('is_renewal');
                }
                if (Schema::hasColumn('payments', 'renewal_reference')) {
                    $table->dropColumn('renewal_reference');
                }
            });
        }

        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $columns = [
                    'provider_subscription_id',
                    'provider',
                    'current_term_starts_at',
                    'current_term_ends_at',
                    'grace_ends_at',
                    'cancel_at_period_end',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('subscriptions', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
