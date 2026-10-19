import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
const CreateArticle = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateArticle.url(options),
    method: 'get',
})

CreateArticle.definition = {
    methods: ["get","head"],
    url: '/admin/articles/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
CreateArticle.url = (options?: RouteQueryOptions) => {
    return CreateArticle.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
CreateArticle.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateArticle.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
CreateArticle.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateArticle.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
    const CreateArticleForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateArticle.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
        CreateArticleForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateArticle.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Articles\Pages\CreateArticle::__invoke
 * @see app/Filament/Resources/Articles/Pages/CreateArticle.php:7
 * @route '/admin/articles/create'
 */
        CreateArticleForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateArticle.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateArticle.form = CreateArticleForm
export default CreateArticle