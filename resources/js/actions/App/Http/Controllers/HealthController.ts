import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
const HealthController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HealthController.url(options),
    method: 'get',
})

HealthController.definition = {
    methods: ["get","head"],
    url: '/health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
HealthController.url = (options?: RouteQueryOptions) => {
    return HealthController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
HealthController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: HealthController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
HealthController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: HealthController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
    const HealthControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: HealthController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
        HealthControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: HealthController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\HealthController::__invoke
 * @see app/Http/Controllers/HealthController.php:15
 * @route '/health'
 */
        HealthControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: HealthController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    HealthController.form = HealthControllerForm
export default HealthController