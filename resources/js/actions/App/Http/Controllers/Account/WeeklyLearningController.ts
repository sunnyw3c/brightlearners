import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
export const showProfile = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showProfile.url(args, options),
    method: 'get',
})

showProfile.definition = {
    methods: ["get","head"],
    url: '/account/profiles/{profile}/weekly-learning',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
showProfile.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { profile: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { profile: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    profile: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        profile: typeof args.profile === 'object'
                ? args.profile.id
                : args.profile,
                }

    return showProfile.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
showProfile.get = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showProfile.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
showProfile.head = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showProfile.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
    const showProfileForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: showProfile.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
        showProfileForm.get = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showProfile.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::showProfile
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
        showProfileForm.head = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showProfile.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    showProfile.form = showProfileForm
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/weekly-learning',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::index
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
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
const WeeklyLearningController = { showProfile, index }

export default WeeklyLearningController