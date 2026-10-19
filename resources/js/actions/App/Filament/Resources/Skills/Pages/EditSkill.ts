import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
const EditSkill = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSkill.url(args, options),
    method: 'get',
})

EditSkill.definition = {
    methods: ["get","head"],
    url: '/admin/skills/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
EditSkill.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditSkill.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
EditSkill.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditSkill.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
EditSkill.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditSkill.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
    const EditSkillForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditSkill.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
        EditSkillForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSkill.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Skills\Pages\EditSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/EditSkill.php:7
 * @route '/admin/skills/{record}/edit'
 */
        EditSkillForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditSkill.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditSkill.form = EditSkillForm
export default EditSkill