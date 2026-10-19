import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
const OverdueReviews = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: OverdueReviews.url(options),
    method: 'get',
})

OverdueReviews.definition = {
    methods: ["get","head"],
    url: '/admin/overdue-reviews',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
OverdueReviews.url = (options?: RouteQueryOptions) => {
    return OverdueReviews.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
OverdueReviews.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: OverdueReviews.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
OverdueReviews.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: OverdueReviews.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
    const OverdueReviewsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: OverdueReviews.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
        OverdueReviewsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: OverdueReviews.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
        OverdueReviewsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: OverdueReviews.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    OverdueReviews.form = OverdueReviewsForm
export default OverdueReviews