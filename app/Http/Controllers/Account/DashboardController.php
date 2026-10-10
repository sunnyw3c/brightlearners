<?php

namespace App\Http\Controllers\Account;

use App\Domains\Access\Models\Download;
use App\Domains\Accounts\Models\LearningProfile;
use App\Domains\Accounts\Services\ActiveProfileManager;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Domains\Learning\Models\ResourceProgress;
use App\Domains\Membership\Contracts\MembershipChecker;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        ActiveProfileManager $profileManager,
        MembershipChecker $membershipChecker,
    ): Response {
        $user = $request->user();
        $requestedProfileId = $request->query('profile_id');

        $activeProfile = $profileManager->getActiveProfile(
            $user,
            $requestedProfileId ? (int) $requestedProfileId : null,
        );

        $profiles = $user->learningProfiles()
            ->where('active', true)
            ->with('schoolClass')
            ->get()
            ->map(fn (LearningProfile $p) => [
                'id' => $p->id,
                'nickname' => $p->nickname,
                'class_id' => $p->class_id,
                'class_name' => $p->schoolClass?->name,
                'avatar_key' => $p->avatar_key,
                'is_active' => $activeProfile?->id === $p->id,
            ]);

        $isActiveMember = $membershipChecker->hasActiveMembership($user);

        $programmeData = null;
        $monthlyProgress = null;
        $nextReleaseDate = null;
        $nextSkillCheck = null;
        $nextAction = null;

        if ($activeProfile && $activeProfile->class_id && $isActiveMember) {
            $currentMonth = (int) now()->format('n');
            $currentYear = (int) now()->format('Y');

            $program = LearningProgram::query()
                ->where('class_id', $activeProfile->class_id)
                ->where('status', LearningProgramStatus::Published)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->with(['weeks.items.resource.currentVersion'])
                ->first();

            if ($program === null) {
                $program = LearningProgram::query()
                    ->where('class_id', $activeProfile->class_id)
                    ->where('status', LearningProgramStatus::Published)
                    ->with(['weeks.items.resource.currentVersion'])
                    ->latest('id')
                    ->first();
            }

            if ($program !== null) {
                $releasedWeeks = $program->weeks->filter(fn (LearningWeek $w) => $w->isReleased())->values();
                $totalTermWeeks = max(12, $program->weeks->count());
                $releasedWeeksCount = $releasedWeeks->count();

                $currentWeek = $releasedWeeks->last() ?? $program->weeks->first();

                $progressRecords = ResourceProgress::query()
                    ->where('learning_profile_id', $activeProfile->id)
                    ->get()
                    ->keyBy('resource_id');

                $currentWeekItems = [];
                if ($currentWeek) {
                    $currentWeekItems = $currentWeek->items->map(function ($item) use ($progressRecords) {
                        $res = $item->resource;
                        $prog = $res ? $progressRecords->get($res->id) : null;
                        $status = $prog?->status->value ?? 'not_started';
                        $subject = $res?->primarySubjectSlug() ? ucfirst($res->primarySubjectSlug()) : 'General';

                        return [
                            'id' => $item->id,
                            'resource_id' => $res?->id,
                            'title' => $res?->title,
                            'slug' => $res?->slug,
                            'subject' => $subject,
                            'type' => $res?->type?->value,
                            'type_label' => $res?->type?->label(),
                            'guidance' => $item->guidance,
                            'required' => $item->required,
                            'current_version' => $res?->currentVersion?->version,
                            'download_url' => $res ? route('downloads.show', $res->slug) : null,
                            'library_url' => $res ? route('account.library.show', $res->slug) : null,
                            'progress_status' => $status,
                            'is_completed' => $status === 'completed',
                        ];
                    });
                }

                // Next action: first incomplete item of this week
                $incompleteItem = collect($currentWeekItems)->first(fn ($item) => ! $item['is_completed']);
                if ($incompleteItem) {
                    $nextAction = [
                        'type' => 'activity',
                        'title' => "Up Next: {$incompleteItem['title']}",
                        'description' => $incompleteItem['guidance'] ?? "Complete this {$incompleteItem['type_label']} activity.",
                        'item' => $incompleteItem,
                    ];
                } elseif ($currentWeek) {
                    $nextAction = [
                        'type' => 'completed',
                        'title' => "Week {$currentWeek->week_number} Complete!",
                        'description' => 'Great job! You have completed all activities for this week.',
                        'item' => null,
                    ];
                }

                // Next release date
                $nextUnreleasedWeek = $program->weeks->first(fn (LearningWeek $w) => ! $w->isReleased());
                if ($nextUnreleasedWeek && $nextUnreleasedWeek->release_at) {
                    $nextReleaseDate = $nextUnreleasedWeek->release_at->format('M d, Y');
                }

                // Next skill check (week 4 of programme)
                $skillCheckWeek = $program->weeks->firstWhere('week_number', 4) ?? $program->weeks->last();
                if ($skillCheckWeek && $skillCheckWeek->release_at) {
                    $nextSkillCheck = [
                        'title' => "Week {$skillCheckWeek->week_number} Skill Review",
                        'date' => $skillCheckWeek->release_at->format('M d, Y'),
                    ];
                }

                // Monthly progress figure (M-08: worded as activities opened / completed, never a score)
                $allReleasedResourceIds = $releasedWeeks->flatMap(fn ($w) => $w->items->pluck('resource_id'))->unique()->filter()->values();
                $totalActivities = $allReleasedResourceIds->count();
                $completedCount = $allReleasedResourceIds->filter(fn ($id) => ($progressRecords->get($id)?->status === ProgressStatus::Completed))->count();
                $openedCount = $allReleasedResourceIds->filter(fn ($id) => in_array($progressRecords->get($id)?->status, [ProgressStatus::Opened, ProgressStatus::Downloaded, ProgressStatus::Completed], true))->count();
                $progressPercentage = $totalActivities > 0 ? (int) round(($completedCount / $totalActivities) * 100) : 0;

                $monthlyProgress = [
                    'total_activities' => $totalActivities,
                    'completed_activities' => $completedCount,
                    'opened_activities' => $openedCount,
                    'percentage' => $progressPercentage,
                    'label' => "{$completedCount} of {$totalActivities} activities completed",
                    'opened_label' => "{$openedCount} of {$totalActivities} packs opened",
                ];

                $programmeData = [
                    'program_id' => $program->id,
                    'program_title' => $program->title,
                    'current_week_number' => $currentWeek?->week_number ?? 1,
                    'term_weeks_total' => $totalTermWeeks,
                    'weeks_released' => $releasedWeeksCount,
                    'week_title' => $currentWeek?->title,
                    'week_emphasis' => $currentWeek?->emphasis,
                    'items' => $currentWeekItems,
                ];
            }
        }

        // Recent downloads
        $recentDownloads = Download::query()
            ->where('user_id', $user->id)
            ->with(['resource.currentVersion'])
            ->latest('downloaded_at')
            ->take(5)
            ->get()
            ->map(fn (Download $d) => [
                'id' => $d->id,
                'resource_id' => $d->resource_id,
                'title' => $d->resource?->title,
                'slug' => $d->resource?->slug,
                'type_label' => $d->resource?->type?->label() ?? 'Resource',
                'downloaded_at' => $d->downloaded_at?->format('M d, Y'),
                'download_url' => $d->resource ? route('downloads.show', $d->resource->slug) : null,
            ]);

        return Inertia::render('account/dashboard', [
            'active_profile' => $activeProfile ? [
                'id' => $activeProfile->id,
                'nickname' => $activeProfile->nickname,
                'class_id' => $activeProfile->class_id,
                'class_name' => $activeProfile->schoolClass?->name,
                'avatar_key' => $activeProfile->avatar_key,
            ] : null,
            'profiles' => $profiles,
            'is_active_member' => $isActiveMember,
            'programme' => $programmeData,
            'monthly_progress' => $monthlyProgress,
            'next_release_date' => $nextReleaseDate,
            'next_skill_check' => $nextSkillCheck,
            'next_action' => $nextAction,
            'recent_downloads' => $recentDownloads,
        ]);
    }

    public function switchProfile(
        Request $request,
        LearningProfile $profile,
        ActiveProfileManager $profileManager,
    ): RedirectResponse {
        $profileManager->setActiveProfile($request->user(), $profile);

        return redirect()->route('account.dashboard');
    }
}
