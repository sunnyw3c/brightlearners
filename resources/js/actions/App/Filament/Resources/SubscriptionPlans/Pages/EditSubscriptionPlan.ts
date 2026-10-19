import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
const EditSubscriptionPlan = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSubscriptionPlan.url(args, options),
    method: 'get',
})

EditSubscriptionPlan.definition = {
    methods: ["get","head"],
    url: '/admin/subscription-plans/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
EditSubscriptionPlan.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return EditSubscriptionPlan.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
EditSubscriptionPlan.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSubscriptionPlan.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
EditSubscriptionPlan.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditSubscriptionPlan.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
    const EditSubscriptionPlanForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditSubscriptionPlan.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
        EditSubscriptionPlanForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSubscriptionPlan.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SubscriptionPlans\Pages\EditSubscriptionPlan::__invoke
 * @see app/Filament/Resources/SubscriptionPlans/Pages/EditSubscriptionPlan.php:7
 * @route '/admin/subscription-plans/{record}/edit'
 */
        EditSubscriptionPlanForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSubscriptionPlan.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditSubscriptionPlan.form = EditSubscriptionPlanForm
export default EditSubscriptionPlan