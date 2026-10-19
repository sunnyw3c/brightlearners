import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
const ClassController = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ClassController.url(args, options),
    method: 'get',
})

ClassController.definition = {
    methods: ["get","head"],
    url: '/{class}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
ClassController.url = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { class: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    class: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        class: args.class,
                }

    return ClassController.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
ClassController.get = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ClassController.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
ClassController.head = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ClassController.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
    const ClassControllerForm = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ClassController.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
        ClassControllerForm.get = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ClassController.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
        ClassControllerForm.head = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ClassController.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ClassController.form = ClassControllerForm
export default ClassController