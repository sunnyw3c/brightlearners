import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
const EditAssessment = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAssessment.url(args, options),
    method: 'get',
})

EditAssessment.definition = {
    methods: ["get","head"],
    url: '/admin/assessments/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
EditAssessment.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditAssessment.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
EditAssessment.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditAssessment.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
EditAssessment.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditAssessment.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
    const EditAssessmentForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditAssessment.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
        EditAssessmentForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditAssessment.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Assessments\Pages\EditAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/EditAssessment.php:7
 * @route '/admin/assessments/{record}/edit'
 */
        EditAssessmentForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditAssessment.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditAssessment.form = EditAssessmentForm
export default EditAssessment