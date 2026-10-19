import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
const PilotCohort = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PilotCohort.url(options),
    method: 'get',
})

PilotCohort.definition = {
    methods: ["get","head"],
    url: '/admin/pilot-cohort',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
PilotCohort.url = (options?: RouteQueryOptions) => {
    return PilotCohort.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
PilotCohort.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PilotCohort.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
PilotCohort.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PilotCohort.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
    const PilotCohortForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: PilotCohort.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
        PilotCohortForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PilotCohort.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
        PilotCohortForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: PilotCohort.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    PilotCohort.form = PilotCohortForm
export default PilotCohort