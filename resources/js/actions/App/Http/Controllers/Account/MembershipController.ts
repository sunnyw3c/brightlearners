import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/membership',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\MembershipController::index
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
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
const MembershipController = { index }

export default MembershipController