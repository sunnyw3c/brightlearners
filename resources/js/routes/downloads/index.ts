import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
export const show = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/download/{resource}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
show.url = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
            args = { resource: args.slug }
        }
    
    if (Array.isArray(args)) {
        args = {
                    resource: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        resource: typeof args.resource === 'object'
                ? args.resource.slug
                : args.resource,
                }

    return show.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
show.get = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
show.head = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
    const showForm = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
        showForm.get = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
        showForm.head = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const downloads = {
    show: Object.assign(show, show),
}

export default downloads