import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\DashboardController::index
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
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
* @see \App\Http\Controllers\Account\DashboardController::switchProfile
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
export const switchProfile = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: switchProfile.url(args, options),
    method: 'post',
})

switchProfile.definition = {
    methods: ["post"],
    url: '/account/profiles/{profile}/switch',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\DashboardController::switchProfile
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
switchProfile.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return switchProfile.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\DashboardController::switchProfile
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
switchProfile.post = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: switchProfile.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\DashboardController::switchProfile
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
    const switchProfileForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: switchProfile.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\DashboardController::switchProfile
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
        switchProfileForm.post = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: switchProfile.url(args, options),
            method: 'post',
        })
    
    switchProfile.form = switchProfileForm
const DashboardController = { index, switchProfile }

export default DashboardController