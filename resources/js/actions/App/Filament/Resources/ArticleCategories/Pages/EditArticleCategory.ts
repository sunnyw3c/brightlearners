import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
const EditArticleCategory = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditArticleCategory.url(args, options),
    method: 'get',
})

EditArticleCategory.definition = {
    methods: ["get","head"],
    url: '/admin/article-categories/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
EditArticleCategory.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return EditArticleCategory.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
EditArticleCategory.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditArticleCategory.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
EditArticleCategory.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditArticleCategory.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
    const EditArticleCategoryForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditArticleCategory.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
        EditArticleCategoryForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditArticleCategory.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\ArticleCategories\Pages\EditArticleCategory::__invoke
 * @see app/Filament/Resources/ArticleCategories/Pages/EditArticleCategory.php:7
 * @route '/admin/article-categories/{record}/edit'
 */
        EditArticleCategoryForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditArticleCategory.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditArticleCategory.form = EditArticleCategoryForm
export default EditArticleCategory