import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
export const alias = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: alias.url(args, options),
    method: 'get',
})

alias.definition = {
    methods: ["get","head"],
    url: '/products/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
alias.url = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { slug: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    slug: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        slug: args.slug,
                }

    return alias.definition.url
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
alias.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: alias.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
alias.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: alias.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
    const aliasForm = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: alias.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
        aliasForm.get = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: alias.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Shop\ShopController::alias
 * @see app/Http/Controllers/Shop/ShopController.php:147
 * @route '/products/{slug}'
 */
        aliasForm.head = (args: { slug: string | number } | [slug: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: alias.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    alias.form = aliasForm
const products = {
    alias: Object.assign(alias, alias),
}

export default products