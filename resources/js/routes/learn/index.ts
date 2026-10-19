import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/learn',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\LearnController::__invoke
 * @see app/Http/Controllers/Learn/LearnController.php:18
 * @route '/learn'
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
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
export const classMethod = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: classMethod.url(args, options),
    method: 'get',
})

classMethod.definition = {
    methods: ["get","head"],
    url: '/{class}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
classMethod.url = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return classMethod.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
classMethod.get = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: classMethod.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
classMethod.head = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: classMethod.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
    const classMethodForm = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: classMethod.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
        classMethodForm.get = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: classMethod.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\ClassController::__invoke
 * @see app/Http/Controllers/Learn/ClassController.php:18
 * @route '/{class}'
 */
        classMethodForm.head = (args: { class: string | number } | [classParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: classMethod.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    classMethod.form = classMethodForm
/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
export const subject = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: subject.url(args, options),
    method: 'get',
})

subject.definition = {
    methods: ["get","head"],
    url: '/{class}/{subject}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
subject.url = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions) => {
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

    return subject.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace('{subject}', parsedArgs.subject.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
subject.get = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: subject.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
subject.head = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: subject.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
    const subjectForm = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: subject.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
        subjectForm.get = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: subject.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\SubjectController::__invoke
 * @see app/Http/Controllers/Learn/SubjectController.php:18
 * @route '/{class}/{subject}'
 */
        subjectForm.head = (args: { class: string | number, subject: string | number } | [classParam: string | number, subject: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: subject.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    subject.form = subjectForm
/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
export const topic = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: topic.url(args, options),
    method: 'get',
})

topic.definition = {
    methods: ["get","head"],
    url: '/{class}/{subject}/{topic}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
topic.url = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions) => {
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

    return topic.definition.url
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
topic.get = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: topic.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
topic.head = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: topic.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
    const topicForm = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: topic.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
        topicForm.get = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: topic.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Learn\TopicController::__invoke
 * @see app/Http/Controllers/Learn/TopicController.php:18
 * @route '/{class}/{subject}/{topic}'
 */
        topicForm.head = (args: { class: string | number, subject: string | number, topic: string | number } | [classParam: string | number, subject: string | number, topic: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: topic.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    topic.form = topicForm
const learn = {
    index: Object.assign(index, index),
class: Object.assign(classMethod, classMethod),
subject: Object.assign(subject, subject),
topic: Object.assign(topic, topic),
}

export default learn