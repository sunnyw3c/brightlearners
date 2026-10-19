import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
const RobotsController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RobotsController.url(options),
    method: 'get',
})

RobotsController.definition = {
    methods: ["get","head"],
    url: '/robots.txt',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
RobotsController.url = (options?: RouteQueryOptions) => {
    return RobotsController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
RobotsController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RobotsController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
RobotsController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RobotsController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
    const RobotsControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RobotsController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
        RobotsControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RobotsController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\RobotsController::__invoke
 * @see app/Http/Controllers/RobotsController.php:13
 * @route '/robots.txt'
 */
        RobotsControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RobotsController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RobotsController.form = RobotsControllerForm
export default RobotsController