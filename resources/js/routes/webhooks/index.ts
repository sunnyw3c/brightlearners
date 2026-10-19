import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
export const razorpay = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: razorpay.url(options),
    method: 'post',
})

razorpay.definition = {
    methods: ["post"],
    url: '/webhooks/razorpay',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
razorpay.url = (options?: RouteQueryOptions) => {
    return razorpay.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
razorpay.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: razorpay.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
    const razorpayForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: razorpay.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
        razorpayForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: razorpay.url(options),
            method: 'post',
        })
    
    razorpay.form = razorpayForm
const webhooks = {
    razorpay: Object.assign(razorpay, razorpay),
}

export default webhooks