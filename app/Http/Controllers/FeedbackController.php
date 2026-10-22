<?php

namespace App\Http\Controllers;

use App\Domains\Accounts\Enums\FeedbackCategory;
use App\Domains\Accounts\Models\Feedback;
use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', Rule::enum(FeedbackCategory::class)],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'message' => ['required', 'string', 'min:5', 'max:2000'],
            'learning_resource_id' => ['nullable', 'integer', 'exists:resources,id'],
            'url' => ['nullable', 'string', 'max:500'],
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user()?->id,
            'learning_resource_id' => $validated['learning_resource_id'] ?? null,
            'category' => $validated['category'],
            'rating' => $validated['rating'] ?? null,
            'message' => $validated['message'],
            'url' => $validated['url'] ?? $request->header('referer'),
            'status' => 'new',
        ]);

        Analytics::track(
            name: EventName::FeedbackSubmitted,
            properties: [
                'feedback_id' => $feedback->id,
                'category' => $feedback->category->value,
            ],
            userId: $request->user()?->id,
            source: 'server',
        );

        return back()->with('success', 'Thank you for your feedback! It helps us improve '.config('app.name', 'KiddoZen').'.');
    }
}
