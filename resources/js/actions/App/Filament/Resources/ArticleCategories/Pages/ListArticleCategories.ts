import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
const ListArticleCategories = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListArticleCategories.url(options),
    method: 'get',
})

ListArticleCategories.definition = {
    methods: ["get","head"],
    url: '/admin/article-categories',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
ListArticleCategories.url = (options?: RouteQueryOptions) => {
    return ListArticleCategories.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
ListArticleCategories.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListArticleCategories.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
ListArticleCategories.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListArticleCategories.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
    const ListArticleCategoriesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListArticleCategories.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
        ListArticleCategoriesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListArticleCategories.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\ArticleCategories\Pages\ListArticleCategories::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/ListArticleCategories.php:7
 * @route '/admin/article-categories'
 */
        ListArticleCategoriesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListArticleCategories.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListArticleCategories.form = ListArticleCategoriesForm
export default ListArticleCategories