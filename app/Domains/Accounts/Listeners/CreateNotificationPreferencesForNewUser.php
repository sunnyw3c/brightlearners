<?php

namespace App\Domains\Accounts\Listeners;

use App\Domains\Accounts\Models\NotificationPreference;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class CreateNotificationPreferencesForNewUser
{
    public function __construct(private Request $request) {}

    /**
     * Carry the registration form's marketing-consent choice into a
     * notification_preferences row. This runs synchronously, inside the
     * registration request, because the consent choice lives on that
     * request and is not stored on the user. Registered manually in
     * AppServiceProvider: Laravel's event auto-discovery only scans
     * app/Listeners, not the domain folders.
     */
    public function handle(Registered $event): void
    {
        $consented = $this->request->boolean('marketing_consent');

        NotificationPreference::query()->firstOrCreate(
            ['user_id' => $event->user->getKey()],
            [
                'marketing_consent' => $consented,
                'marketing_consent_at' => $consented ? now() : null,
                'marketing_consent_source' => $consented ? 'registration' : null,
            ],
        );
    }
}
