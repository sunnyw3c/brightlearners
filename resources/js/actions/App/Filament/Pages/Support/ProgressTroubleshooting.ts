import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
const ProgressTroubleshooting = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ProgressTroubleshooting.url(options),
    method: 'get',
})

ProgressTroubleshooting.definition = {
    methods: ["get","head"],
    url: '/admin/progress-troubleshooting',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
ProgressTroubleshooting.url = (options?: RouteQueryOptions) => {
    return ProgressTroubleshooting.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
ProgressTroubleshooting.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ProgressTroubleshooting.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
ProgressTroubleshooting.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ProgressTroubleshooting.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
    const ProgressTroubleshootingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ProgressTroubleshooting.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
        ProgressTroubleshootingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ProgressTroubleshooting.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
        ProgressTroubleshootingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ProgressTroubleshooting.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ProgressTroubleshooting.form = ProgressTroubleshootingForm
export default ProgressTroubleshooting