import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
const LearnController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: LearnController.url(options),
    method: 'get',
})

LearnController.definition = {
    methods: ["get","head"],
    url: '/learn',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
LearnController.url = (options?: RouteQueryOptions) => {
    return LearnController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
LearnController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: LearnController.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
LearnController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: LearnController.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
    const LearnControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: LearnController.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
        LearnControllerForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: LearnController.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
        LearnControllerForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: LearnController.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    LearnController.form = LearnControllerForm
export default LearnController