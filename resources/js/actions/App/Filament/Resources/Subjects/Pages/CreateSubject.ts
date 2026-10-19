import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
const CreateSubject = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubject.url(options),
    method: 'get',
})

CreateSubject.definition = {
    methods: ["get","head"],
    url: '/admin/subjects/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
CreateSubject.url = (options?: RouteQueryOptions) => {
    return CreateSubject.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
CreateSubject.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSubject.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
CreateSubject.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSubject.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
    const CreateSubjectForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateSubject.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
        CreateSubjectForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSubject.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Subjects\Pages\CreateSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/CreateSubject.php:7
 * @route '/admin/subjects/create'
 */
        CreateSubjectForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSubject.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateSubject.form = CreateSubjectForm
export default CreateSubject