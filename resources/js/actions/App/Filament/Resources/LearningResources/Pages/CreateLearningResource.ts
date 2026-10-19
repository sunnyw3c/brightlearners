import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
const CreateLearningResource = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLearningResource.url(options),
    method: 'get',
})

CreateLearningResource.definition = {
    methods: ["get","head"],
    url: '/admin/learning-resources/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
CreateLearningResource.url = (options?: RouteQueryOptions) => {
    return CreateLearningResource.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
CreateLearningResource.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLearningResource.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
CreateLearningResource.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLearningResource.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
    const CreateLearningResourceForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateLearningResource.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
        CreateLearningResourceForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateLearningResource.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\LearningResources\Pages\CreateLearningResource::__invoke
 * @see app/Filament/Resources/LearningResources/Pages/CreateLearningResource.php:7
 * @route '/admin/learning-resources/create'
 */
        CreateLearningResourceForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateLearningResource.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateLearningResource.form = CreateLearningResourceForm
export default CreateLearningResource