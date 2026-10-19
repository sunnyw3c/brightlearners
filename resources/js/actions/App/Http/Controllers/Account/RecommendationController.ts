import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/recommendations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\RecommendationController::index
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
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
* @see \App\Http\Controllers\Account\RecommendationController::recordClick
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
export const recordClick = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordClick.url(args, options),
    method: 'post',
})

recordClick.definition = {
    methods: ["post"],
    url: '/account/recommendations/{recommendation}/click',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\RecommendationController::recordClick
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
recordClick.url = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { recommendation: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { recommendation: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    recommendation: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        recommendation: typeof args.recommendation === 'object'
                ? args.recommendation.id
                : args.recommendation,
                }

    return recordClick.definition.url
            .replace('{recommendation}', parsedArgs.recommendation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\RecommendationController::recordClick
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
recordClick.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordClick.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\RecommendationController::recordClick
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
    const recordClickForm = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordClick.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\RecommendationController::recordClick
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
        recordClickForm.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: recordClick.url(args, options),
            method: 'post',
        })
    
    recordClick.form = recordClickForm
/**
* @see \App\Http\Controllers\Account\RecommendationController::recordActed
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
export const recordActed = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordActed.url(args, options),
    method: 'post',
})

recordActed.definition = {
    methods: ["post"],
    url: '/account/recommendations/{recommendation}/act',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\RecommendationController::recordActed
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
recordActed.url = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { recommendation: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { recommendation: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    recommendation: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        recommendation: typeof args.recommendation === 'object'
                ? args.recommendation.id
                : args.recommendation,
                }

    return recordActed.definition.url
            .replace('{recommendation}', parsedArgs.recommendation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\RecommendationController::recordActed
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
recordActed.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: recordActed.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\RecommendationController::recordActed
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
    const recordActedForm = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: recordActed.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\RecommendationController::recordActed
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
        recordActedForm.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: recordActed.url(args, options),
            method: 'post',
        })
    
    recordActed.form = recordActedForm
const RecommendationController = { index, recordClick, recordActed }

export default RecommendationController