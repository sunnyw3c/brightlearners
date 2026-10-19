import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
const ViewLearningResource = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewLearningResource.url(args, options),
    method: 'get',
})

ViewLearningResource.definition = {
    methods: ["get","head"],
    url: '/admin/learning-resources/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
ViewLearningResource.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { record: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    record: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        record: args.record,
                }

    return ViewLearningResource.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
ViewLearningResource.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewLearningResource.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
ViewLearningResource.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewLearningResource.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
    const ViewLearningResourceForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ViewLearningResource.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
        ViewLearningResourceForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ViewLearningResource.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\LearningResources\Pages\ViewLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/ViewLearningResource.php:7
 * @route '/admin/learning-resources/{record}'
 */
        ViewLearningResourceForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ViewLearningResource.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ViewLearningResource.form = ViewLearningResourceForm
export default ViewLearningResource