<?php

namespace App\Http\Controllers;

use App\Domains\Catalog\Models\Product;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Models\SubscriptionPlan;
use App\Support\Data\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MembershipController extends Controller
{
    public function index(Request $request, MembershipChecker $membershipChecker): Response
    {
        $plan = SubscriptionPlan::query()->where('code', 'pilot_90')->first();
        $product = Product::query()->where('slug', 'weekly-learning-programme-90-days')->first();

        $isActiveMember = $membershipChecker->hasActiveMembership($request->user());

        return Inertia::render('membership/index', [
            'plan' => $plan ? [
                'code' => $plan->code,
                'name' => $plan->name,
                'price' => $plan->price,
                'price_formatted' => '₹'.number_format($plan->price / 100),
                'duration_days' => $plan->duration_days,
                'benefits' => $plan->benefits,
            ] : null,
            'product' => $product ? [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->regular_price,
                'price_formatted' => '₹'.number_format($product->regular_price / 100),
            ] : null,
            'is_active_member' => $isActiveMember,
            'seo' => new Seo(
                title: 'Weekly Learning Programme (90 Days) — Structured Home Learning | BrightLearners',
                description: 'A 90-day structured home learning programme for primary school children. Weekly printable packs, parent answer keys, and progressive mastery.',
                canonical: route('membership.index'),
            ),
        ]);
    }

    public function sample(): Response
    {
        return Inertia::render('membership/sample-month', [
            'sample' => [
                'title' => 'Sample Learning Month: Class 2 Core Foundations',
                'description' => 'A representative 4-week learning plan demonstrating our structured, screen-free routine with parent guidance notes.',
                'weeks' => [
                    [
                        'week_number' => 1,
                        'title' => 'Week 1: Addition & Sight Words',
                        'emphasis' => '2-digit addition without carrying and high-frequency English vocabulary.',
                        'items' => [
                            ['title' => '2-Digit Addition Practice', 'type' => 'Worksheet', 'guidance' => '15 minutes: review place value blocks before starting.'],
                            ['title' => 'Sight Words Word Search', 'type' => 'Activity', 'guidance' => 'Read words aloud together.'],
                            ['title' => 'Plants & Trees Around Us', 'type' => 'Reading', 'guidance' => 'Discuss tree leaves seen in the park.'],
                        ],
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'Week 2: Subtraction & Phonics Blends',
                        'emphasis' => 'Basic subtraction and consonant blends (bl, cl, fl).',
                        'items' => [
                            ['title' => 'Subtraction within 50', 'type' => 'Worksheet', 'guidance' => 'Use a number line for visual support.'],
                            ['title' => 'Consonant Blends Matching', 'type' => 'Game', 'guidance' => 'Sound out each starting blend.'],
                            ['title' => 'Living vs Non-Living Things', 'type' => 'Worksheet', 'guidance' => 'Categorise 10 household items.'],
                        ],
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'Week 3: Word Problems & Sentence Building',
                        'emphasis' => 'Translating real-world stories into math sums and simple punctuation.',
                        'items' => [
                            ['title' => 'Story Sums in the Market', 'type' => 'Worksheet', 'guidance' => 'Highlight key numbers in each question.'],
                            ['title' => 'Capital Letters and Full Stops', 'type' => 'Worksheet', 'guidance' => 'Spot the 5 punctuation errors.'],
                            ['title' => 'Animal Habitats and Food', 'type' => 'Activity', 'guidance' => 'Match animals with their homes.'],
                        ],
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'Week 4: Review, Puzzle & Mastery Check',
                        'emphasis' => 'Consolidating the month’s learning with fun cross-subject challenges.',
                        'items' => [
                            ['title' => 'Month 1 Mathematics Check-Up', 'type' => 'Starter Check', 'guidance' => 'Gentle 20-minute independent review.'],
                            ['title' => 'English Grammar Crossword', 'type' => 'Game', 'guidance' => 'Parent and child collaborative puzzle.'],
                            ['title' => 'My Family & Helpers Certificate', 'type' => 'Activity', 'guidance' => 'Celebrate completing 4 weeks of consistent work!'],
                        ],
                    ],
                ],
            ],
            'seo' => new Seo(
                title: 'Sample Learning Month: Class 2 Core Foundations | BrightLearners',
                description: 'Explore a representative 4-week sample of our structured home learning routine with worksheet previews and parent guidance notes.',
                canonical: route('membership.sample'),
            ),
        ]);
    }
}
