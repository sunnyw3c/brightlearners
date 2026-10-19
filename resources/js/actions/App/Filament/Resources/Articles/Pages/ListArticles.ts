import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
const ListArticles = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListArticles.url(options),
    method: 'get',
})

ListArticles.definition = {
    methods: ["get","head"],
    url: '/admin/articles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
ListArticles.url = (options?: RouteQueryOptions) => {
    return ListArticles.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
ListArticles.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListArticles.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
ListArticles.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListArticles.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
    const ListArticlesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListArticles.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
        ListArticlesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListArticles.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Articles\Pages\ListArticles::__invoke
 * @see app/Filament/Resources/Articles/Pages/ListArticles.php:7
 * @route '/admin/articles'
 */
        ListArticlesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListArticles.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListArticles.form = ListArticlesForm
export default ListArticles