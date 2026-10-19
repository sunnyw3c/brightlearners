import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
const SubjectController = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SubjectController.url(args, options),
    method: 'get',
})

SubjectController.definition = {
    methods: ["get","head"],
    url: '/{class}/{subject}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
SubjectController.url = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    class: args[0],
                    subject: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        class: args.class,
                                subject: args.subject,
                }

    return SubjectController.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace('{subject}', parsedArgs.subject.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
SubjectController.get = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: SubjectController.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
SubjectController.head = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: SubjectController.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
    const SubjectControllerForm = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: SubjectController.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
        SubjectControllerForm.get = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: SubjectController.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
        SubjectControllerForm.head = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: SubjectController.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    SubjectController.form = SubjectControllerForm
export default SubjectController