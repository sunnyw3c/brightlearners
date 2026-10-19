import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/parents',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ParentHubController::index
 * @see app/Http/Controllers/ParentHubController.php:17
 * @route '/parents'
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
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
export const category = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: category.url(args, options),
    method: 'get',
})

category.definition = {
    methods: ["get","head"],
    url: '/parents/{category}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
category.url = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { category: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'slug' in args) {
            args = { category: args.slug }
        }
    
    if (Array.isArray(args)) {
        args = {
                    category: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        category: typeof args.category === 'object'
                ? args.category.slug
                : args.category,
                }

    return category.definition.url
            .replace('{category}', parsedArgs.category.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
category.get = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: category.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
category.head = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: category.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
    const categoryForm = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: category.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
        categoryForm.get = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: category.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ParentHubController::category
 * @see app/Http/Controllers/ParentHubController.php:45
 * @route '/parents/{category}'
 */
        categoryForm.head = (args: { category: string | { slug: string } } | [category: string | { slug: string } ] | string | { slug: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: category.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    category.form = categoryForm
const ParentHubController = { index, category }

export default ParentHubController