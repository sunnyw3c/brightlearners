import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
const DownloadController = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DownloadController.url(args, options),
    method: 'get',
})

DownloadController.definition = {
    methods: ["get","head"],
    url: '/download/{resource}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
DownloadController.url = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
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

    return DownloadController.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
DownloadController.get = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: DownloadController.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
DownloadController.head = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: DownloadController.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
    const DownloadControllerForm = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: DownloadController.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
        DownloadControllerForm.get = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: DownloadController.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\DownloadController::__invoke
 * @see app/Http/Controllers/DownloadController.php:14
 * @route '/download/{resource}'
 */
        DownloadControllerForm.head = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: DownloadController.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    DownloadController.form = DownloadControllerForm
export default DownloadController