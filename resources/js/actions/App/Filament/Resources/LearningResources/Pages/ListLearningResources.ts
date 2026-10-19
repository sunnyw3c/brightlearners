import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
const ListLearningResources = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLearningResources.url(options),
    method: 'get',
})

ListLearningResources.definition = {
    methods: ["get","head"],
    url: '/admin/learning-resources',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
ListLearningResources.url = (options?: RouteQueryOptions) => {
    return ListLearningResources.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
ListLearningResources.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLearningResources.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
ListLearningResources.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLearningResources.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
    const ListLearningResourcesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListLearningResources.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
        ListLearningResourcesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListLearningResources.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\LearningResources\Pages\ListLearningResources::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ListLearningResources.php:7
 * @route '/admin/learning-resources'
 */
        ListLearningResourcesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListLearningResources.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListLearningResources.form = ListLearningResourcesForm
export default ListLearningResources