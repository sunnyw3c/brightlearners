import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
const AnalyticsDashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AnalyticsDashboard.url(options),
    method: 'get',
})

AnalyticsDashboard.definition = {
    methods: ["get","head"],
    url: '/admin/analytics-dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
AnalyticsDashboard.url = (options?: RouteQueryOptions) => {
    return AnalyticsDashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
AnalyticsDashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: AnalyticsDashboard.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
AnalyticsDashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: AnalyticsDashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
    const AnalyticsDashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: AnalyticsDashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
        AnalyticsDashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AnalyticsDashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
        AnalyticsDashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: AnalyticsDashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    AnalyticsDashboard.form = AnalyticsDashboardForm
export default AnalyticsDashboard