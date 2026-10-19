import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
const CreateTopic = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateTopic.url(options),
    method: 'get',
})

CreateTopic.definition = {
    methods: ["get","head"],
    url: '/admin/topics/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
CreateTopic.url = (options?: RouteQueryOptions) => {
    return CreateTopic.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
CreateTopic.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateTopic.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
CreateTopic.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateTopic.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
    const CreateTopicForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateTopic.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
        CreateTopicForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateTopic.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Topics\Pages\CreateTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/CreateTopic.php:7
 * @route '/admin/topics/create'
 */
        CreateTopicForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateTopic.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateTopic.form = CreateTopicForm
export default CreateTopic