import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
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
const razorpay = {
    callback: Object.assign(callback, callback),
}

export default razorpay