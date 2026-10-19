import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
const CreateRedirect = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRedirect.url(options),
    method: 'get',
})

CreateRedirect.definition = {
    methods: ["get","head"],
    url: '/admin/redirects/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
CreateRedirect.url = (options?: RouteQueryOptions) => {
    return CreateRedirect.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
CreateRedirect.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateRedirect.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
CreateRedirect.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateRedirect.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
    const CreateRedirectForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateRedirect.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
        CreateRedirectForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateRedirect.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Redirects\Pages\CreateRedirect::__invoke
 * @see app/Filament/Resources/Redirects/Pages/CreateRedirect.php:7
 * @route '/admin/redirects/create'
 */
        CreateRedirectForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateRedirect.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateRedirect.form = CreateRedirectForm
export default CreateRedirect