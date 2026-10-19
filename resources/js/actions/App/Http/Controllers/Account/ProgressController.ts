import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/progress',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\ProgressController::index
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
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
* @see \App\Http\Controllers\Account\ProgressController::store
 * @see app/Http/Controllers/Account/ProgressController.php:85
 * @route '/account/progress'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/account/progress',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\ProgressController::store
 * @see app/Http/Controllers/Account/ProgressController.php:85
 * @route '/account/progress'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\ProgressController::store
 * @see app/Http/Controllers/Account/ProgressController.php:85
 * @route '/account/progress'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\ProgressController::store
 * @see app/Http/Controllers/Account/ProgressController.php:85
 * @route '/account/progress'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\ProgressController::store
 * @see app/Http/Controllers/Account/ProgressController.php:85
 * @route '/account/progress'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const ProgressController = { index, store }

export default ProgressController