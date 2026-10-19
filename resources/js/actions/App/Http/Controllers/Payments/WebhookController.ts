import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
const WebhookController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: WebhookController.url(options),
    method: 'post',
})

WebhookController.definition = {
    methods: ["post"],
    url: '/webhooks/razorpay',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
WebhookController.url = (options?: RouteQueryOptions) => {
    return WebhookController.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
WebhookController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: WebhookController.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
    const WebhookControllerForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: WebhookController.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Payments\WebhookController::__invoke
 * @see app/Http/Controllers/Payments/WebhookController.php:15
 * @route '/webhooks/razorpay'
 */
        WebhookControllerForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: WebhookController.url(options),
            method: 'post',
        })
    
    WebhookController.form = WebhookControllerForm
export default WebhookController