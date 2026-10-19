import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
const CreateSchoolClass = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSchoolClass.url(options),
    method: 'get',
})

CreateSchoolClass.definition = {
    methods: ["get","head"],
    url: '/admin/school-classes/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
CreateSchoolClass.url = (options?: RouteQueryOptions) => {
    return CreateSchoolClass.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
CreateSchoolClass.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSchoolClass.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
CreateSchoolClass.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSchoolClass.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
    const CreateSchoolClassForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateSchoolClass.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
        CreateSchoolClassForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSchoolClass.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
        CreateSchoolClassForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSchoolClass.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateSchoolClass.form = CreateSchoolClassForm
export default CreateSchoolClass