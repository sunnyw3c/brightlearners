import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
const ListSubjects = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubjects.url(options),
    method: 'get',
})

ListSubjects.definition = {
    methods: ["get","head"],
    url: '/admin/subjects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
ListSubjects.url = (options?: RouteQueryOptions) => {
    return ListSubjects.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
ListSubjects.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSubjects.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
ListSubjects.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSubjects.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
    const ListSubjectsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSubjects.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
        ListSubjectsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSubjects.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Subjects\Pages\ListSubjects::__invoke
 * @see app/Filament/Resources/Subjects/Pages/ListSubjects.php:7
 * @route '/admin/subjects'
 */
        ListSubjectsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSubjects.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSubjects.form = ListSubjectsForm
export default ListSubjects