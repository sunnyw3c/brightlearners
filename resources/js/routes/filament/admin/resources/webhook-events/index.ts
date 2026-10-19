import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/webhook-events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
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
const webhookEvents = {
    index: Object.assign(index, index),
}

export default webhookEvents