import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
const feedback = {
    index: Object.assign(index, index),
}

export default feedback