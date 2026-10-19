import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\RecommendationController::click
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
export const click = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: click.url(args, options),
    method: 'post',
})

click.definition = {
    methods: ["post"],
    url: '/account/recommendations/{recommendation}/click',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\RecommendationController::click
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
click.url = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return click.definition.url
            .replace('{recommendation}', parsedArgs.recommendation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\RecommendationController::click
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
click.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: click.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\RecommendationController::click
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
    const clickForm = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: click.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\RecommendationController::click
 * @see app/Http/Controllers/Account/RecommendationController.php:86
 * @route '/account/recommendations/{recommendation}/click'
 */
        clickForm.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: click.url(args, options),
            method: 'post',
        })
    
    click.form = clickForm
/**
* @see \App\Http\Controllers\Account\RecommendationController::act
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
export const act = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: act.url(args, options),
    method: 'post',
})

act.definition = {
    methods: ["post"],
    url: '/account/recommendations/{recommendation}/act',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\RecommendationController::act
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
act.url = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return act.definition.url
            .replace('{recommendation}', parsedArgs.recommendation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\RecommendationController::act
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
act.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: act.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\RecommendationController::act
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
    const actForm = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: act.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\RecommendationController::act
 * @see app/Http/Controllers/Account/RecommendationController.php:112
 * @route '/account/recommendations/{recommendation}/act'
 */
        actForm.post = (args: { recommendation: number | { id: number } } | [recommendation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: act.url(args, options),
            method: 'post',
        })
    
    act.form = actForm
const recommendations = {
    click: Object.assign(click, click),
act: Object.assign(act, act),
}

export default recommendations