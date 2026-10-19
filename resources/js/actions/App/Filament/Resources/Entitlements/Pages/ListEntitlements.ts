import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
const ListEntitlements = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListEntitlements.url(options),
    method: 'get',
})

ListEntitlements.definition = {
    methods: ["get","head"],
    url: '/admin/entitlements',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
ListEntitlements.url = (options?: RouteQueryOptions) => {
    return ListEntitlements.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
ListEntitlements.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListEntitlements.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
ListEntitlements.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListEntitlements.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
    const ListEntitlementsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListEntitlements.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
        ListEntitlementsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListEntitlements.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
        ListEntitlementsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListEntitlements.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListEntitlements.form = ListEntitlementsForm
export default ListEntitlements