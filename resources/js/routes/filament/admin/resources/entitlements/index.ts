import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/entitlements',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Entitlements\Pages\ListEntitlements::__invoke
 * @see app/Filament/Resources/Entitlements/Pages/ListEntitlements.php:7
 * @route '/admin/entitlements'
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
const entitlements = {
    index: Object.assign(index, index),
}

export default entitlements