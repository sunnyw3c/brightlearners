import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
const ReviewQueue = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ReviewQueue.url(options),
    method: 'get',
})

ReviewQueue.definition = {
    methods: ["get","head"],
    url: '/admin/review-queue',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
ReviewQueue.url = (options?: RouteQueryOptions) => {
    return ReviewQueue.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
ReviewQueue.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ReviewQueue.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
ReviewQueue.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ReviewQueue.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
    const ReviewQueueForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ReviewQueue.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
        ReviewQueueForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ReviewQueue.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
        ReviewQueueForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ReviewQueue.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ReviewQueue.form = ReviewQueueForm
export default ReviewQueue