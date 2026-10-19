import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/library',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\LibraryController::index
 * @see app/Http/Controllers/Account/LibraryController.php:24
 * @route '/account/library'
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
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
 */
export const show = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/account/library/{resource}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
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
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
 */
show.get = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
 */
show.head = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
 */
    const showForm = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
 */
        showForm.get = (args: { resource: string | { slug: string } } | [resource: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\LibraryController::show
 * @see app/Http/Controllers/Account/LibraryController.php:176
 * @route '/account/library/{resource}'
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
const library = {
    index: Object.assign(index, index),
show: Object.assign(show, show),
}

export default library