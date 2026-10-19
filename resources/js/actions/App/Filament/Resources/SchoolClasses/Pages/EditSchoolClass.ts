import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
const EditSchoolClass = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSchoolClass.url(args, options),
    method: 'get',
})

EditSchoolClass.definition = {
    methods: ["get","head"],
    url: '/admin/school-classes/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
EditSchoolClass.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditSchoolClass.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
EditSchoolClass.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSchoolClass.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
EditSchoolClass.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditSchoolClass.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
    const EditSchoolClassForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditSchoolClass.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
        EditSchoolClassForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSchoolClass.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
        EditSchoolClassForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSchoolClass.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditSchoolClass.form = EditSchoolClassForm
export default EditSchoolClass