import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
const ListSchoolClasses = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSchoolClasses.url(options),
    method: 'get',
})

ListSchoolClasses.definition = {
    methods: ["get","head"],
    url: '/admin/school-classes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
ListSchoolClasses.url = (options?: RouteQueryOptions) => {
    return ListSchoolClasses.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
ListSchoolClasses.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSchoolClasses.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
ListSchoolClasses.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSchoolClasses.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
    const ListSchoolClassesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSchoolClasses.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
        ListSchoolClassesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSchoolClasses.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
        ListSchoolClassesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSchoolClasses.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSchoolClasses.form = ListSchoolClassesForm
export default ListSchoolClasses