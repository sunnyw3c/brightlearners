import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
const ListLearningPrograms = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLearningPrograms.url(options),
    method: 'get',
})

ListLearningPrograms.definition = {
    methods: ["get","head"],
    url: '/admin/learning-programs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
ListLearningPrograms.url = (options?: RouteQueryOptions) => {
    return ListLearningPrograms.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
ListLearningPrograms.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListLearningPrograms.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
ListLearningPrograms.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListLearningPrograms.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
    const ListLearningProgramsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListLearningPrograms.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
        ListLearningProgramsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListLearningPrograms.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\LearningPrograms\Pages\ListLearningPrograms::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/ListLearningPrograms.php:7
 * @route '/admin/learning-programs'
 */
        ListLearningProgramsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListLearningPrograms.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListLearningPrograms.form = ListLearningProgramsForm
export default ListLearningPrograms