import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/assessments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::index
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::saveFeedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
export const saveFeedback = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: saveFeedback.url(args, options),
    method: 'post',
})

saveFeedback.definition = {
    methods: ["post"],
    url: '/account/assessments/{attempt}/feedback',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::saveFeedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
saveFeedback.url = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return saveFeedback.definition.url
            .replace('{attempt}', parsedArgs.attempt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::saveFeedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
saveFeedback.post = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: saveFeedback.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::saveFeedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
    const saveFeedbackForm = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: saveFeedback.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::saveFeedback
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:66
 * @route '/account/assessments/{attempt}/feedback'
 */
        saveFeedbackForm.post = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: saveFeedback.url(args, options),
            method: 'post',
        })
    
    saveFeedback.form = saveFeedbackForm
const AccountAssessmentController = { index, saveFeedback }

export default AccountAssessmentController