<?php

namespace App\Domains\Learning\Actions;

use App\Domains\Learning\Models\InteractiveExercise;

class AutoGradeExercise
{
    /**
     * Auto-grade an interactive exercise submission.
     *
     * @param  array<int|string, mixed>|string|int  $submittedAnswers
     * @return array<string, mixed>
     */
    public function handle(InteractiveExercise $exercise, mixed $submittedAnswers): array
    {
        if (! is_array($submittedAnswers)) {
            $submittedAnswers = [$submittedAnswers];
        }

        $config = $exercise->question_config ?? [];
        if (isset($config['questions']) && is_array($config['questions'])) {
            $questions = $config['questions'];
        } elseif (isset($config['correct_value']) || isset($config['correct_answer']) || isset($config['question']) || isset($config['prompt'])) {
            $questions = [$config];
        } else {
            $questions = is_array($config) ? $config : [];
        }

        $totalQuestions = count($questions);
        $correctCount = 0;
        $feedback = [];

        foreach ($questions as $index => $q) {
            $submitted = $submittedAnswers[$index] ?? $submittedAnswers['answer'] ?? ($index === 0 && isset($submittedAnswers[0]) ? $submittedAnswers[0] : null);
            $expected = $q['correct_value'] ?? $q['correct_answer'] ?? null;

            $isCorrect = $this->evaluateAnswer($submitted, $expected);
            if ($isCorrect) {
                $correctCount++;
            }

            $explanation = $q['explanation'] ?? ($isCorrect ? 'Well done!' : 'Try again next time!');

            $feedback[$index] = [
                'prompt' => $q['prompt'] ?? $q['question'] ?? '',
                'submitted' => $submitted,
                'is_correct' => $isCorrect,
                'explanation' => $explanation,
            ];
        }

        $percentage = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
        $isSingle = $totalQuestions === 1;
        $singleExplanation = $isSingle ? ($feedback[0]['explanation'] ?? '') : null;

        return [
            'exercise_id' => $exercise->id,
            'title' => $exercise->title,
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'percentage' => $percentage,
            'score' => $percentage,
            'is_correct' => $totalQuestions > 0 && $correctCount === $totalQuestions,
            'feedback' => $isSingle ? ($correctCount === 1 ? 'Excellent! Correct answer.' : 'Incorrect, review explanation.') : $feedback,
            'explanation' => $singleExplanation,
            'details' => $feedback,
            'passed' => $percentage >= 70,
        ];
    }

    private function evaluateAnswer(mixed $submitted, mixed $expected): bool
    {
        if ($submitted === null || $expected === null) {
            return false;
        }

        $cleanSubmitted = strtolower(trim((string) $submitted));
        $cleanExpected = strtolower(trim((string) $expected));

        return $cleanSubmitted === $cleanExpected;
    }
}
