import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
const CorrectionQueue = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CorrectionQueue.url(options),
    method: 'get',
})

CorrectionQueue.definition = {
    methods: ["get","head"],
    url: '/admin/correction-queue',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
CorrectionQueue.url = (options?: RouteQueryOptions) => {
    return CorrectionQueue.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
CorrectionQueue.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CorrectionQueue.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
CorrectionQueue.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CorrectionQueue.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
    const CorrectionQueueForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CorrectionQueue.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
        CorrectionQueueForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CorrectionQueue.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
        CorrectionQueueForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CorrectionQueue.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CorrectionQueue.form = CorrectionQueueForm
export default CorrectionQueue