import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
const ListResourceCorrections = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListResourceCorrections.url(options),
    method: 'get',
})

ListResourceCorrections.definition = {
    methods: ["get","head"],
    url: '/admin/resource-corrections',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
ListResourceCorrections.url = (options?: RouteQueryOptions) => {
    return ListResourceCorrections.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
ListResourceCorrections.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListResourceCorrections.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
ListResourceCorrections.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListResourceCorrections.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
    const ListResourceCorrectionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListResourceCorrections.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
        ListResourceCorrectionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListResourceCorrections.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\ResourceCorrections\Pages\ListResourceCorrections::__invoke
 * @see app/Filament/Resources/ResourceCorrections/Pages/ListResourceCorrections.php:7
 * @route '/admin/resource-corrections'
 */
        ListResourceCorrectionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListResourceCorrections.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListResourceCorrections.form = ListResourceCorrectionsForm
export default ListResourceCorrections