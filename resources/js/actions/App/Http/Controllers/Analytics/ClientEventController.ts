import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Analytics\ClientEventController::store
 * @see app/Http/Controllers/Analytics/ClientEventController.php:15
 * @route '/events'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/events',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Analytics\ClientEventController::store
 * @see app/Http/Controllers/Analytics/ClientEventController.php:15
 * @route '/events'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Analytics\ClientEventController::store
 * @see app/Http/Controllers/Analytics/ClientEventController.php:15
 * @route '/events'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Analytics\ClientEventController::store
 * @see app/Http/Controllers/Analytics/ClientEventController.php:15
 * @route '/events'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Analytics\ClientEventController::store
 * @see app/Http/Controllers/Analytics/ClientEventController.php:15
 * @route '/events'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const ClientEventController = { store }

export default ClientEventController