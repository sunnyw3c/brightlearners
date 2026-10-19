import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
const CreateArticleCategory = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateArticleCategory.url(options),
    method: 'get',
})

CreateArticleCategory.definition = {
    methods: ["get","head"],
    url: '/admin/article-categories/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
CreateArticleCategory.url = (options?: RouteQueryOptions) => {
    return CreateArticleCategory.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
CreateArticleCategory.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateArticleCategory.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
CreateArticleCategory.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateArticleCategory.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
    const CreateArticleCategoryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateArticleCategory.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
        CreateArticleCategoryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateArticleCategory.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\ArticleCategories\Pages\CreateArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/CreateArticleCategory.php:7
 * @route '/admin/article-categories/create'
 */
        CreateArticleCategoryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateArticleCategory.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateArticleCategory.form = CreateArticleCategoryForm
export default CreateArticleCategory