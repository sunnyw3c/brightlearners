import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
const ListTopics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTopics.url(options),
    method: 'get',
})

ListTopics.definition = {
    methods: ["get","head"],
    url: '/admin/topics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
ListTopics.url = (options?: RouteQueryOptions) => {
    return ListTopics.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
ListTopics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListTopics.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
ListTopics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListTopics.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
    const ListTopicsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListTopics.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
        ListTopicsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListTopics.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Topics\Pages\ListTopics::__invoke
 * @see app/Filament/Resources/Topics/Pages/ListTopics.php:7
 * @route '/admin/topics'
 */
        ListTopicsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListTopics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListTopics.form = ListTopicsForm
export default ListTopics