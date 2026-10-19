import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Payments\PaymentController::store
 * @see app/Http/Controllers/Payments/PaymentController.php:19
 * @route '/checkout/payment'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/checkout/payment',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Payments\PaymentController::store
 * @see app/Http/Controllers/Payments/PaymentController.php:19
 * @route '/checkout/payment'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Payments\PaymentController::store
 * @see app/Http/Controllers/Payments/PaymentController.php:19
 * @route '/checkout/payment'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Payments\PaymentController::store
 * @see app/Http/Controllers/Payments/PaymentController.php:19
 * @route '/checkout/payment'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Payments\PaymentController::store
 * @see app/Http/Controllers/Payments/PaymentController.php:19
 * @route '/checkout/payment'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Payments\PaymentController::callback
 * @see app/Http/Controllers/Payments/PaymentController.php:43
 * @route '/payments/razorpay/callback'
 */
export const callback = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: callback.url(options),
    method: 'post',
})

callback.definition = {
    methods: ["post"],
    url: '/payments/razorpay/callback',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Payments\PaymentController::callback
 * @see app/Http/Controllers/Payments/PaymentController.php:43
 * @route '/payments/razorpay/callback'
 */
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Payments\PaymentController::callback
 * @see app/Http/Controllers/Payments/PaymentController.php:43
 * @route '/payments/razorpay/callback'
 */
callback.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: callback.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Payments\PaymentController::callback
 * @see app/Http/Controllers/Payments/PaymentController.php:43
 * @route '/payments/razorpay/callback'
 */
    const callbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: callback.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Payments\PaymentController::callback
 * @see app/Http/Controllers/Payments/PaymentController.php:43
 * @route '/payments/razorpay/callback'
 */
        callbackForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: callback.url(options),
            method: 'post',
        })
    
    callback.form = callbackForm
const PaymentController = { store, callback }

export default PaymentController