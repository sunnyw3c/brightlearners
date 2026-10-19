import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import items from './items'
import coupon from './coupon'
/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/cart',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
    const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
        showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
        showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const cart = {
    show: Object.assign(show, show),
items: Object.assign(items, items),
coupon: Object.assign(coupon, coupon),
}

export default cart