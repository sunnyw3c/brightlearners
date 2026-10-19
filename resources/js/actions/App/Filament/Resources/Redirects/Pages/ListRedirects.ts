import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
const ListRedirects = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRedirects.url(options),
    method: 'get',
})

ListRedirects.definition = {
    methods: ["get","head"],
    url: '/admin/redirects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
ListRedirects.url = (options?: RouteQueryOptions) => {
    return ListRedirects.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
ListRedirects.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListRedirects.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
ListRedirects.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListRedirects.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
    const ListRedirectsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListRedirects.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
        ListRedirectsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListRedirects.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Redirects\Pages\ListRedirects::__invoke
 * @see app/Filament/Resources/Redirects/Pages/ListRedirects.php:7
 * @route '/admin/redirects'
 */
        ListRedirectsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListRedirects.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListRedirects.form = ListRedirectsForm
export default ListRedirects