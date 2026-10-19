import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::feedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
export const feedback = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: feedback.url(args, options),
    method: 'post',
})

feedback.definition = {
    methods: ["post"],
    url: '/account/assessments/{attempt}/feedback',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::feedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
feedback.url = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { attempt: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { attempt: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    attempt: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        attempt: typeof args.attempt === 'object'
                ? args.attempt.id
                : args.attempt,
                }

    return feedback.definition.url
            .replace('{attempt}', parsedArgs.attempt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::feedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
feedback.post = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: feedback.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::feedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
    const feedbackForm = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: feedback.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::feedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
        feedbackForm.post = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: feedback.url(args, options),
            method: 'post',
        })
    
    feedback.form = feedbackForm
const assessments = {
    feedback: Object.assign(feedback, feedback),
}

export default assessments