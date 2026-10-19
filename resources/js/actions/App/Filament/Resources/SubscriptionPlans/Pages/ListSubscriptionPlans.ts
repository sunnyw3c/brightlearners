import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
const ListSubscriptionPlans = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptionPlans.url(options),
    method: 'get',
})

ListSubscriptionPlans.definition = {
    methods: ["get","head"],
    url: '/admin/subscription-plans',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
ListSubscriptionPlans.url = (options?: RouteQueryOptions) => {
    return ListSubscriptionPlans.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
ListSubscriptionPlans.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubscriptionPlans.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
ListSubscriptionPlans.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSubscriptionPlans.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
    const ListSubscriptionPlansForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSubscriptionPlans.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
        ListSubscriptionPlansForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSubscriptionPlans.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\ListSubscriptionPlans::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/ListSubscriptionPlans.php:7
 * @route '/admin/subscription-plans'
 */
        ListSubscriptionPlansForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSubscriptionPlans.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSubscriptionPlans.form = ListSubscriptionPlansForm
export default ListSubscriptionPlans