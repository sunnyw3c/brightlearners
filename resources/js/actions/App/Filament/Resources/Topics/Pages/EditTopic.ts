import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
const EditTopic = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditTopic.url(args, options),
    method: 'get',
})

EditTopic.definition = {
    methods: ["get","head"],
    url: '/admin/topics/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
EditTopic.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditTopic.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
EditTopic.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditTopic.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
EditTopic.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditTopic.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
    const EditTopicForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditTopic.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
        EditTopicForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditTopic.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Topics\Pages\EditTopic::__invoke
 * @see app/Filament/Resources/Topics/Pages/EditTopic.php:7
 * @route '/admin/topics/{record}/edit'
 */
        EditTopicForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditTopic.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditTopic.form = EditTopicForm
export default EditTopic