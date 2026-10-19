import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
const ListAssessments = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAssessments.url(options),
    method: 'get',
})

ListAssessments.definition = {
    methods: ["get","head"],
    url: '/admin/assessments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
ListAssessments.url = (options?: RouteQueryOptions) => {
    return ListAssessments.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
ListAssessments.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAssessments.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
ListAssessments.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListAssessments.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
    const ListAssessmentsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListAssessments.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
        ListAssessmentsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListAssessments.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Assessments\Pages\ListAssessments::__invoke
 * @see app/Filament/Resources/Assessments/Pages/ListAssessments.php:7
 * @route '/admin/assessments'
 */
        ListAssessmentsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListAssessments.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListAssessments.form = ListAssessmentsForm
export default ListAssessments