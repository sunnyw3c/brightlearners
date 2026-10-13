<?php

namespace App\Domains\Learning\Actions;

use App\Domains\Analytics\Enums\EventName;
use App\Domains\Analytics\Services\Analytics;
use App\Domains\Learning\Enums\AttemptStatus;
use App\Domains\Learning\Enums\QuestionType;
use App\Domains\Learning\Enums\SkillBand;
use App\Domains\Learning\Events\AssessmentCompleted;
use App\Domains\Learning\Models\AssessmentAnswer;
use App\Domains\Learning\Models\AssessmentAttempt;
use App\Domains\Learning\Models\AssessmentQuestion;
use App\Domains\Learning\Models\SkillScore;
use Illuminate\Support\Facades\DB;

class ScoreAssessmentAttempt
{
    public function __construct(
        private readonly GenerateRecommendations $generateRecommendations,
    ) {}

    /**
     * Score an assessment attempt.
     * Scoring is a pure function of the answers and the assessment version.
     * Results are mapped to simple bands per skill, never precise-looking numbers.
     *
     * @param  array<int, mixed>  $submittedAnswers  keyed by question_id => response
     */
    public function handle(AssessmentAttempt $attempt, array $submittedAnswers = []): AssessmentAttempt
    {
        return DB::transaction(function () use ($attempt, $submittedAnswers) {
            $attempt->loadMissing(['assessment.questions', 'learningProfile']);

            $questions = $attempt->assessment->questions;

            // 1. Process each question answer
            $skillTotals = []; // [skill_id => ['earned' => int, 'total' => int]]

            foreach ($questions as $question) {
                /** @var AssessmentQuestion $question */
                $skillId = $question->skill_id;
                if (! isset($skillTotals[$skillId])) {
                    $skillTotals[$skillId] = ['earned' => 0, 'total' => 0];
                }

                $rawResponse = $submittedAnswers[$question->id] ?? null;
                $isCorrect = $this->evaluateResponse($question, $rawResponse);
                $pointsEarned = $isCorrect ? $question->points : 0;

                $skillTotals[$skillId]['earned'] += $pointsEarned;
                $skillTotals[$skillId]['total'] += $question->points;

                AssessmentAnswer::query()->updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'response' => is_array($rawResponse) ? $rawResponse : ['value' => $rawResponse],
                        'is_correct' => $isCorrect,
                        'score' => $pointsEarned,
                    ]
                );
            }

            // 2. Score by skill into simple bands
            $skillBands = [];
            foreach ($skillTotals as $skillId => $totals) {
                $percentage = $totals['total'] > 0
                    ? (int) round(($totals['earned'] / $totals['total']) * 100)
                    : 0;

                $band = SkillBand::fromPercentage($percentage);
                $skillBands[$skillId] = $band->value;

                SkillScore::query()->updateOrCreate(
                    [
                        'learning_profile_id' => $attempt->learning_profile_id,
                        'skill_id' => $skillId,
                        'source_attempt_id' => $attempt->id,
                    ],
                    [
                        'score' => $percentage,
                        'band' => $band,
                        'calculated_at' => now(),
                    ]
                );
            }

            // 3. Mark attempt completed
            $attempt->status = AttemptStatus::Completed;
            $attempt->completed_at = now();
            $attempt->save();

            // 4. Generate recommendations based on the updated skill profile
            if ($attempt->learningProfile !== null) {
                $this->generateRecommendations->handle($attempt->learningProfile);
            }

            // 5. Track analytics event
            Analytics::track(
                name: EventName::AssessmentCompleted,
                properties: [
                    'assessment_id' => $attempt->assessment_id,
                    'profile_id' => $attempt->learning_profile_id,
                    'skill_bands' => $skillBands,
                ],
                userId: $attempt->user_id,
                source: 'server',
            );

            AssessmentCompleted::dispatch($attempt);

            return $attempt;
        });
    }

    /**
     * Pure evaluator for answers based on question config and type.
     */
    public function evaluateResponse(AssessmentQuestion $question, mixed $response): bool
    {
        if ($response === null) {
            return false;
        }

        $config = $question->answer_config ?? [];

        return match ($question->type) {
            QuestionType::SingleChoice => $this->evaluateSingleChoice($config, $response),
            QuestionType::MultipleChoice => $this->evaluateMultipleChoice($config, $response),
            QuestionType::Text => $this->evaluateText($config, $response),
        };
    }

    private function evaluateSingleChoice(array $config, mixed $response): bool
    {
        $submitted = is_array($response) ? ($response['selected'] ?? $response['value'] ?? null) : $response;
        $expected = $config['correct_answer'] ?? null;

        if ($submitted === null || $expected === null) {
            return false;
        }

        return trim((string) $submitted) === trim((string) $expected);
    }

    private function evaluateMultipleChoice(array $config, mixed $response): bool
    {
        $submitted = is_array($response) ? ($response['selected'] ?? $response) : [$response];
        $expected = (array) ($config['correct_answers'] ?? [$config['correct_answer'] ?? '']);

        $normalizedSubmitted = array_map(fn ($item) => trim((string) $item), (array) $submitted);
        $normalizedExpected = array_map(fn ($item) => trim((string) $item), $expected);

        sort($normalizedSubmitted);
        sort($normalizedExpected);

        return $normalizedSubmitted === $normalizedExpected;
    }

    private function evaluateText(array $config, mixed $response): bool
    {
        $submitted = is_array($response) ? ($response['value'] ?? '') : (string) $response;
        $submitted = strtolower(trim((string) $submitted));

        $accepted = array_map('strtolower', array_map('trim', (array) ($config['accepted_answers'] ?? [$config['correct_answer'] ?? ''])));

        return in_array($submitted, $accepted, true);
    }
}
