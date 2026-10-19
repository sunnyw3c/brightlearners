import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/free',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Free\FreeResourceController::index
 * @see app/Http/Controllers/Free/FreeResourceController.php:23
 * @route '/free'
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
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
export const show = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/free/{class}/{subject}/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
show.url = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    class: args[0],
                    subject: args[1],
                    slug: args[2],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        class: args.class,
                                subject: args.subject,
                                slug: args.slug,
                }

    return show.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace('{subject}', parsedArgs.subject.toString())
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
show.get = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
show.head = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
    const showForm = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
        showForm.get = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Free\FreeResourceController::show
 * @see app/Http/Controllers/Free/FreeResourceController.php:49
 * @route '/free/{class}/{subject}/{slug}'
 */
        showForm.head = (args: { class: string | number, subject: string | number, slug: string | number } | [classParam: string | number, subject: string | number, slug: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const FreeResourceController = { index, show }

export default FreeResourceController