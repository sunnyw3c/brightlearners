import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
const CreateSkill = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSkill.url(options),
    method: 'get',
})

CreateSkill.definition = {
    methods: ["get","head"],
    url: '/admin/skills/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
CreateSkill.url = (options?: RouteQueryOptions) => {
    return CreateSkill.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
CreateSkill.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateSkill.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
CreateSkill.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateSkill.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
    const CreateSkillForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateSkill.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
        CreateSkillForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSkill.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Skills\Pages\CreateSkill::__invoke
 * @see app/Filament/Resources/Skills/Pages/CreateSkill.php:7
 * @route '/admin/skills/create'
 */
        CreateSkillForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateSkill.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateSkill.form = CreateSkillForm
export default CreateSkill