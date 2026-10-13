<?php

use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Learning\Actions\RecordProgress;
use App\Domains\Learning\Enums\ProgressStatus;
use App\Http\Middleware\AuthenticateApiToken;
use App\Http\Resources\LearningResourceResource;
use App\Http\Resources\SchoolClassResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public Discovery Endpoints
    Route::get('/curriculum', function () {
        $classes = SchoolClass::query()->with('subjects')->orderBy('sort_order')->get();

        return SchoolClassResource::collection($classes);
    });

    Route::get('/resources', function () {
        $resources = LearningResource::query()
            ->where('status', ResourceStatus::Published)
            ->latest('published_at')
            ->paginate(15);

        return LearningResourceResource::collection($resources);
    });

    Route::get('/resources/{slug}', function (string $slug) {
        $resource = LearningResource::query()
            ->where('slug', $slug)
            ->where('status', ResourceStatus::Published)
            ->firstOrFail();

        return new LearningResourceResource($resource);
    });

    // Authenticated Mobile / Client Endpoints
    Route::middleware(AuthenticateApiToken::class)->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json([
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ]);
        });

        Route::get('/profiles', function (Request $request) {
            $profiles = $request->user()->learningProfiles()->where('active', true)->with('schoolClass')->get();

            return response()->json([
                'profiles' => $profiles->map(fn ($p) => [
                    'id' => $p->id,
                    'nickname' => $p->nickname,
                    'class_name' => $p->schoolClass?->name,
                ]),
            ]);
        });

        Route::post('/progress', function (Request $request, RecordProgress $recordProgress) {
            $validated = $request->validate([
                'profile_id' => ['required', 'integer'],
                'resource_id' => ['required', 'integer'],
                'status' => ['required', 'string', 'in:opened,downloaded,completed'],
            ]);

            $profile = $request->user()->learningProfiles()->findOrFail($validated['profile_id']);
            $resource = LearningResource::query()->findOrFail($validated['resource_id']);

            $progress = $recordProgress->handle(
                user: $request->user(),
                profile: $profile,
                resource: $resource,
                targetStatus: ProgressStatus::from($validated['status']),
                sourceContext: 'api_mobile',
            );

            return response()->json([
                'status' => 'ok',
                'progress' => [
                    'id' => $progress->id,
                    'status' => $progress->status->value,
                ],
            ]);
        });
    });
});
