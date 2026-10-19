import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
const CreateSubscriptionPlan = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubscriptionPlan.url(options),
    method: 'get',
})

CreateSubscriptionPlan.definition = {
    methods: ["get","head"],
    url: '/admin/subscription-plans/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
CreateSubscriptionPlan.url = (options?: RouteQueryOptions) => {
    return CreateSubscriptionPlan.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
CreateSubscriptionPlan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubscriptionPlan.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
CreateSubscriptionPlan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSubscriptionPlan.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
    const CreateSubscriptionPlanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateSubscriptionPlan.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
        CreateSubscriptionPlanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSubscriptionPlan.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\CreateSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/CreateSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/create'
 */
        CreateSubscriptionPlanForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSubscriptionPlan.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateSubscriptionPlan.form = CreateSubscriptionPlanForm
export default CreateSubscriptionPlan