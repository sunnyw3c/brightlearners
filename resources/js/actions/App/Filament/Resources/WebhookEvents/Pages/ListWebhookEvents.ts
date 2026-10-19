import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
const ListWebhookEvents = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWebhookEvents.url(options),
    method: 'get',
})

ListWebhookEvents.definition = {
    methods: ["get","head"],
    url: '/admin/webhook-events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
ListWebhookEvents.url = (options?: RouteQueryOptions) => {
    return ListWebhookEvents.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
ListWebhookEvents.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListWebhookEvents.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
ListWebhookEvents.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListWebhookEvents.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
    const ListWebhookEventsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListWebhookEvents.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
        ListWebhookEventsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWebhookEvents.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\WebhookEvents\Pages\ListWebhookEvents::__invoke
 * @see app/Filament/Resources/WebhookEvents/Pages/ListWebhookEvents.php:7
 * @route '/admin/webhook-events'
 */
        ListWebhookEventsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListWebhookEvents.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListWebhookEvents.form = ListWebhookEventsForm
export default ListWebhookEvents