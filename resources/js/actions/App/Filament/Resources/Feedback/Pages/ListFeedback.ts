import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
const ListFeedback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListFeedback.url(options),
    method: 'get',
})

ListFeedback.definition = {
    methods: ["get","head"],
    url: '/admin/feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
ListFeedback.url = (options?: RouteQueryOptions) => {
    return ListFeedback.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
ListFeedback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListFeedback.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
ListFeedback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListFeedback.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
    const ListFeedbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListFeedback.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
        ListFeedbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListFeedback.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Feedback\Pages\ListFeedback::__invoke
 * @see app/Filament/Resources/Feedback/Pages/ListFeedback.php:7
 * @route '/admin/feedback'
 */
        ListFeedbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListFeedback.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListFeedback.form = ListFeedbackForm
export default ListFeedback