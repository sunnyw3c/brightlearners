import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
export const success = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: success.url(args, options),
    method: 'get',
})

success.definition = {
    methods: ["get","head"],
    url: '/order/{order}/success',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
success.url = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { order: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { order: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    order: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        order: typeof args.order === 'object'
                ? args.order.id
                : args.order,
                }

    return success.definition.url
            .replace('{order}', parsedArgs.order.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
success.get = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: success.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
success.head = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: success.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
    const successForm = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: success.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
        successForm.get = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: success.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Commerce\OrderResultController::success
 * @see app/Http/Controllers/Commerce/OrderResultController.php:17
 * @route '/order/{order}/success'
 */
        successForm.head = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: success.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    success.form = successForm
/**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
export const paymentPending = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: paymentPending.url(args, options),
    method: 'get',
})

paymentPending.definition = {
    methods: ["get","head"],
    url: '/order/{order}/payment-pending',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
paymentPending.url = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { order: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { order: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    order: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        order: typeof args.order === 'object'
                ? args.order.id
                : args.order,
                }

    return paymentPending.definition.url
            .replace('{order}', parsedArgs.order.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
paymentPending.get = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: paymentPending.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
paymentPending.head = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: paymentPending.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
    const paymentPendingForm = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: paymentPending.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
        paymentPendingForm.get = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: paymentPending.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Commerce\OrderResultController::paymentPending
 * @see app/Http/Controllers/Commerce/OrderResultController.php:52
 * @route '/order/{order}/payment-pending'
 */
        paymentPendingForm.head = (args: { order: number | { id: number } } | [order: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: paymentPending.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    paymentPending.form = paymentPendingForm
const orders = {
    success: Object.assign(success, success),
paymentPending: Object.assign(paymentPending, paymentPending),
}

export default orders