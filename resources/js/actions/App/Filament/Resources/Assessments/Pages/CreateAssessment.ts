import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
const CreateAssessment = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateAssessment.url(options),
    method: 'get',
})

CreateAssessment.definition = {
    methods: ["get","head"],
    url: '/admin/assessments/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
CreateAssessment.url = (options?: RouteQueryOptions) => {
    return CreateAssessment.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
CreateAssessment.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateAssessment.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
CreateAssessment.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateAssessment.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
    const CreateAssessmentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateAssessment.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
        CreateAssessmentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateAssessment.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Assessments\Pages\CreateAssessment::__invoke
 * @see app/Filament/Resources/Assessments/Pages/CreateAssessment.php:7
 * @route '/admin/assessments/create'
 */
        CreateAssessmentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateAssessment.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateAssessment.form = CreateAssessmentForm
export default CreateAssessment