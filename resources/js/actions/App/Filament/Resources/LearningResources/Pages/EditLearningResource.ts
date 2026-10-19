import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
const EditLearningResource = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLearningResource.url(args, options),
    method: 'get',
})

EditLearningResource.definition = {
    methods: ["get","head"],
    url: '/admin/learning-resources/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
EditLearningResource.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditLearningResource.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
EditLearningResource.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditLearningResource.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
EditLearningResource.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditLearningResource.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
    const EditLearningResourceForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditLearningResource.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
        EditLearningResourceForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditLearningResource.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\LearningResources\Pages\EditLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/EditLearningResource.php:7
 * @route '/admin/learning-resources/{record}/edit'
 */
        EditLearningResourceForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditLearningResource.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditLearningResource.form = EditLearningResourceForm
export default EditLearningResource