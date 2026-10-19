import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
export const show = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/assessment/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
show.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return show.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
show.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
show.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
    const showForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
        showForm.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AssessmentController::show
 * @see app/Http/Controllers/AssessmentController.php:28
 * @route '/assessment/{slug}'
 */
        showForm.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\AssessmentController::startAttempt
 * @see app/Http/Controllers/AssessmentController.php:82
 * @route '/assessment/{slug}/attempt'
 */
export const startAttempt = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: startAttempt.url(args, options),
    method: 'post',
})

startAttempt.definition = {
    methods: ["post"],
    url: '/assessment/{slug}/attempt',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AssessmentController::startAttempt
 * @see app/Http/Controllers/AssessmentController.php:82
 * @route '/assessment/{slug}/attempt'
 */
startAttempt.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return startAttempt.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssessmentController::startAttempt
 * @see app/Http/Controllers/AssessmentController.php:82
 * @route '/assessment/{slug}/attempt'
 */
startAttempt.post = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: startAttempt.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AssessmentController::startAttempt
 * @see app/Http/Controllers/AssessmentController.php:82
 * @route '/assessment/{slug}/attempt'
 */
    const startAttemptForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: startAttempt.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssessmentController::startAttempt
 * @see app/Http/Controllers/AssessmentController.php:82
 * @route '/assessment/{slug}/attempt'
 */
        startAttemptForm.post = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: startAttempt.url(args, options),
            method: 'post',
        })
    
    startAttempt.form = startAttemptForm
/**
* @see \App\Http\Controllers\AssessmentController::submitAttempt
 * @see app/Http/Controllers/AssessmentController.php:122
 * @route '/assessment/attempt/{attempt}/submit'
 */
export const submitAttempt = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submitAttempt.url(args, options),
    method: 'post',
})

submitAttempt.definition = {
    methods: ["post"],
    url: '/assessment/attempt/{attempt}/submit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AssessmentController::submitAttempt
 * @see app/Http/Controllers/AssessmentController.php:122
 * @route '/assessment/attempt/{attempt}/submit'
 */
submitAttempt.url = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return submitAttempt.definition.url
            .replace('{attempt}', parsedArgs.attempt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssessmentController::submitAttempt
 * @see app/Http/Controllers/AssessmentController.php:122
 * @route '/assessment/attempt/{attempt}/submit'
 */
submitAttempt.post = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: submitAttempt.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AssessmentController::submitAttempt
 * @see app/Http/Controllers/AssessmentController.php:122
 * @route '/assessment/attempt/{attempt}/submit'
 */
    const submitAttemptForm = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: submitAttempt.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AssessmentController::submitAttempt
 * @see app/Http/Controllers/AssessmentController.php:122
 * @route '/assessment/attempt/{attempt}/submit'
 */
        submitAttemptForm.post = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: submitAttempt.url(args, options),
            method: 'post',
        })
    
    submitAttempt.form = submitAttemptForm
/**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
export const result = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: result.url(args, options),
    method: 'get',
})

result.definition = {
    methods: ["get","head"],
    url: '/assessment/attempt/{attempt}/result',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
result.url = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return result.definition.url
            .replace('{attempt}', parsedArgs.attempt.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
result.get = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: result.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
result.head = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: result.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
    const resultForm = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: result.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
        resultForm.get = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: result.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AssessmentController::result
 * @see app/Http/Controllers/AssessmentController.php:140
 * @route '/assessment/attempt/{attempt}/result'
 */
        resultForm.head = (args: { attempt: number | { id: number } } | [attempt: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: result.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    result.form = resultForm
const assessments = {
    show: Object.assign(show, show),
startAttempt: Object.assign(startAttempt, startAttempt),
submitAttempt: Object.assign(submitAttempt, submitAttempt),
result: Object.assign(result, result),
}

export default assessments