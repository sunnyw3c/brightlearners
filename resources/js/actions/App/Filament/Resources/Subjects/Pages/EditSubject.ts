import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
const EditSubject = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSubject.url(args, options),
    method: 'get',
})

EditSubject.definition = {
    methods: ["get","head"],
    url: '/admin/subjects/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
EditSubject.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditSubject.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
EditSubject.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSubject.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
EditSubject.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditSubject.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
    const EditSubjectForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditSubject.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
        EditSubjectForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSubject.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Subjects\Pages\EditSubject::__invoke
 * @see app/Filament/Resources/Subjects/Pages/EditSubject.php:7
 * @route '/admin/subjects/{record}/edit'
 */
        EditSubjectForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSubject.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditSubject.form = EditSubjectForm
export default EditSubject