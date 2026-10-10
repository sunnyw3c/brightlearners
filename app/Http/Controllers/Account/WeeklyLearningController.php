<?php

namespace App\Http\Controllers\Account;

use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Accounts\Services\ActiveProfileManager;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyLearningController extends Controller
{
    public function index(Request $request, MembershipChecker $membershipChecker): Response
    {
        $user = $request->user();
        $isActiveMember = $membershipChecker->hasActiveMembership($user);

        $profiles = $user->learningProfiles()
            ->where('active', true)
            ->with('schoolClass')
            ->get();

        $currentMonth = (int) now()->format('n');
        $currentYear = (int) now()->format('Y');

        $programmes = $profiles->map(function ($profile) use ($currentMonth, $currentYear) {
            if ($profile->class_id === null) {
                return [
                    'profile_id' => $profile->id,
                    'profile_name' => $profile->nickname,
                    'class_name' => null,
                    'program' => null,
                ];
            }

            // Find current month published programme, or latest published programme
            $program = LearningProgram::query()
                ->where('class_id', $profile->class_id)
                ->where('status', LearningProgramStatus::Published)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->with(['weeks.items.resource.currentVersion'])
                ->first();

            if ($program === null) {
                $program = LearningProgram::query()
                    ->where('class_id', $profile->class_id)
                    ->where('status', LearningProgramStatus::Published)
                    ->with(['weeks.items.resource.currentVersion'])
                    ->latest('id')
                    ->first();
            }

            $weeksData = [];
            if ($program !== null) {
                $weeksData = $program->weeks->map(function (LearningWeek $week) {
                    $isReleased = $week->isReleased();

                    return [
                        'id' => $week->id,
                        'week_number' => $week->week_number,
                        'title' => $week->title,
                        'emphasis' => $week->emphasis,
                        'is_released' => $isReleased,
                        'release_at' => $week->release_at->format('M d, Y H:i'),
                        'released_at' => $week->released_at?->format('M d, Y'),
                        'items' => $isReleased ? $week->items->map(function ($item) {
                            $res = $item->resource;

                            return [
                                'id' => $item->id,
                                'resource_id' => $res?->id,
                                'title' => $res?->title,
                                'slug' => $res?->slug,
                                'type' => $res?->type->value,
                                'type_label' => $res?->type->label(),
                                'guidance' => $item->guidance,
                                'required' => $item->required,
                                'current_version' => $res?->currentVersion?->version,
                                'download_url' => $res ? route('downloads.show', $res->slug) : null,
                            ];
                        }) : [],
                    ];
                });
            }

            return [
                'profile_id' => $profile->id,
                'profile_name' => $profile->nickname,
                'class_name' => $profile->schoolClass?->name,
                'program' => $program ? [
                    'id' => $program->id,
                    'title' => $program->title,
                    'month' => $program->month,
                    'year' => $program->year,
                    'weeks' => $weeksData,
                ] : null,
            ];
        });

        return Inertia::render('account/weekly-learning', [
            'is_active_member' => $isActiveMember,
            'programmes' => $programmes,
        ]);
    }

    public function showProfile(
        Request $request,
        LearningProfile $profile,
        ActiveProfileManager $profileManager,
    ): RedirectResponse {
        $profileManager->setActiveProfile($request->user(), $profile);

        return redirect()->route('account.weekly-learning');
    }
}
