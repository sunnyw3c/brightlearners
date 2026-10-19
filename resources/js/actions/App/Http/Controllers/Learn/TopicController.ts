import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
const TopicController = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: TopicController.url(args, options),
    method: 'get',
})

TopicController.definition = {
    methods: ["get","head"],
    url: '/{class}/{subject}/{topic}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
TopicController.url = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    class: args[0],
                    subject: args[1],
                    topic: args[2],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        class: args.class,
                                subject: args.subject,
                                topic: args.topic,
                }

    return TopicController.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace('{subject}', parsedArgs.subject.toString())
            .replace('{topic}', parsedArgs.topic.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
TopicController.get = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: TopicController.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
TopicController.head = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: TopicController.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
    const TopicControllerForm = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: TopicController.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
        TopicControllerForm.get = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: TopicController.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
        TopicControllerForm.head = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: TopicController.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    TopicController.form = TopicControllerForm
export default TopicController