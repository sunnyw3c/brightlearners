import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/shop',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Shop\ShopController::index
 * @see app/Http/Controllers/Shop/ShopController.php:25
 * @route '/shop'
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
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
export const type = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: type.url(args, options),
    method: 'get',
})

type.definition = {
    methods: ["get","head"],
    url: '/shop/{type}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
type.url = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { type: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        type: args.type,
                }

    return type.definition.url
            .replace('{type}', parsedArgs.type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
type.get = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: type.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
type.head = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: type.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
    const typeForm = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: type.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
        typeForm.get = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: type.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Shop\ShopController::type
 * @see app/Http/Controllers/Shop/ShopController.php:50
 * @route '/shop/{type}'
 */
        typeForm.head = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: type.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    type.form = typeForm
/**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
export const show = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/shop/{class}/{slug}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
show.url = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    class: args[0],
                    slug: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        class: args.class,
                                slug: args.slug,
                }

    return show.definition.url
            .replace('{class}', parsedArgs.class.toString())
            .replace('{slug}', parsedArgs.slug.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
show.get = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
show.head = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
    const showForm = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
        showForm.get = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Shop\ShopController::show
 * @see app/Http/Controllers/Shop/ShopController.php:90
 * @route '/shop/{class}/{slug}'
 */
        showForm.head = (args: { class: string | number, slug: string | number } | [classParam: string | number, slug: string | number ], options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
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
const ShopController = { index, type, show, alias }

export default ShopController