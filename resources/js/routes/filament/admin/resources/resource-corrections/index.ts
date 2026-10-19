import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/resource-corrections',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
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
const resourceCorrections = {
    index: Object.assign(index, index),
}

export default resourceCorrections