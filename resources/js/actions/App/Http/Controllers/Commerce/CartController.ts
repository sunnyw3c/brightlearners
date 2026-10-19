import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/cart',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
    const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
        showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Commerce\CartController::show
 * @see app/Http/Controllers/Commerce/CartController.php:24
 * @route '/cart'
 */
        showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\Commerce\CartController::addItem
 * @see app/Http/Controllers/Commerce/CartController.php:37
 * @route '/cart/items'
 */
export const addItem = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addItem.url(options),
    method: 'post',
})

addItem.definition = {
    methods: ["post"],
    url: '/cart/items',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Commerce\CartController::addItem
 * @see app/Http/Controllers/Commerce/CartController.php:37
 * @route '/cart/items'
 */
addItem.url = (options?: RouteQueryOptions) => {
    return addItem.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CartController::addItem
 * @see app/Http/Controllers/Commerce/CartController.php:37
 * @route '/cart/items'
 */
addItem.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addItem.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Commerce\CartController::addItem
 * @see app/Http/Controllers/Commerce/CartController.php:37
 * @route '/cart/items'
 */
    const addItemForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: addItem.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Commerce\CartController::addItem
 * @see app/Http/Controllers/Commerce/CartController.php:37
 * @route '/cart/items'
 */
        addItemForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: addItem.url(options),
            method: 'post',
        })
    
    addItem.form = addItemForm
/**
* @see \App\Http\Controllers\Commerce\CartController::removeItem
 * @see app/Http/Controllers/Commerce/CartController.php:47
 * @route '/cart/items/{product}'
 */
export const removeItem = (args: { product: number | { id: number } } | [product: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removeItem.url(args, options),
    method: 'delete',
})

removeItem.definition = {
    methods: ["delete"],
    url: '/cart/items/{product}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Commerce\CartController::removeItem
 * @see app/Http/Controllers/Commerce/CartController.php:47
 * @route '/cart/items/{product}'
 */
removeItem.url = (args: { product: number | { id: number } } | [product: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { product: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { product: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    product: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        product: typeof args.product === 'object'
                ? args.product.id
                : args.product,
                }

    return removeItem.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CartController::removeItem
 * @see app/Http/Controllers/Commerce/CartController.php:47
 * @route '/cart/items/{product}'
 */
removeItem.delete = (args: { product: number | { id: number } } | [product: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removeItem.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Commerce\CartController::removeItem
 * @see app/Http/Controllers/Commerce/CartController.php:47
 * @route '/cart/items/{product}'
 */
    const removeItemForm = (args: { product: number | { id: number } } | [product: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: removeItem.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Commerce\CartController::removeItem
 * @see app/Http/Controllers/Commerce/CartController.php:47
 * @route '/cart/items/{product}'
 */
        removeItemForm.delete = (args: { product: number | { id: number } } | [product: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: removeItem.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    removeItem.form = removeItemForm
/**
* @see \App\Http\Controllers\Commerce\CartController::applyCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:55
 * @route '/cart/coupon'
 */
export const applyCoupon = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: applyCoupon.url(options),
    method: 'post',
})

applyCoupon.definition = {
    methods: ["post"],
    url: '/cart/coupon',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Commerce\CartController::applyCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:55
 * @route '/cart/coupon'
 */
applyCoupon.url = (options?: RouteQueryOptions) => {
    return applyCoupon.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CartController::applyCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:55
 * @route '/cart/coupon'
 */
applyCoupon.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: applyCoupon.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Commerce\CartController::applyCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:55
 * @route '/cart/coupon'
 */
    const applyCouponForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: applyCoupon.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Commerce\CartController::applyCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:55
 * @route '/cart/coupon'
 */
        applyCouponForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: applyCoupon.url(options),
            method: 'post',
        })
    
    applyCoupon.form = applyCouponForm
/**
* @see \App\Http\Controllers\Commerce\CartController::removeCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:63
 * @route '/cart/coupon'
 */
export const removeCoupon = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removeCoupon.url(options),
    method: 'delete',
})

removeCoupon.definition = {
    methods: ["delete"],
    url: '/cart/coupon',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Commerce\CartController::removeCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:63
 * @route '/cart/coupon'
 */
removeCoupon.url = (options?: RouteQueryOptions) => {
    return removeCoupon.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\CartController::removeCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:63
 * @route '/cart/coupon'
 */
removeCoupon.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: removeCoupon.url(options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Commerce\CartController::removeCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:63
 * @route '/cart/coupon'
 */
    const removeCouponForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: removeCoupon.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Commerce\CartController::removeCoupon
 * @see app/Http/Controllers/Commerce/CartController.php:63
 * @route '/cart/coupon'
 */
        removeCouponForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: removeCoupon.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    removeCoupon.form = removeCouponForm
const CartController = { show, addItem, removeItem, applyCoupon, removeCoupon }

export default CartController