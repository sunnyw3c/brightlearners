import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/checkout',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
 */
    const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
 */
        showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Commerce\CheckoutController::show
 * @see app/Http/Controllers/Commerce/CheckoutController.php:24
 * @route '/checkout'
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
/**
* @see \App\Http\Controllers\Commerce\CheckoutController::store
 * @see app/Http/Controllers/Commerce/CheckoutController.php:46
 * @route '/checkout'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/checkout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Commerce\CheckoutController::store
 * @see app/Http/Controllers/Commerce/CheckoutController.php:46
 * @route '/checkout'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CheckoutController::store
 * @see app/Http/Controllers/Commerce/CheckoutController.php:46
 * @route '/checkout'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Commerce\CheckoutController::store
 * @see app/Http/Controllers/Commerce/CheckoutController.php:46
 * @route '/checkout'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Commerce\CheckoutController::store
 * @see app/Http/Controllers/Commerce/CheckoutController.php:46
 * @route '/checkout'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const CheckoutController = { show, store }

export default CheckoutController