import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
const ListSkills = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSkills.url(options),
    method: 'get',
})

ListSkills.definition = {
    methods: ["get","head"],
    url: '/admin/skills',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
ListSkills.url = (options?: RouteQueryOptions) => {
    return ListSkills.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
ListSkills.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListSkills.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
ListSkills.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListSkills.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
    const ListSkillsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListSkills.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
        ListSkillsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSkills.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Skills\Pages\ListSkills::__invoke
 * @see app/Filament/Resources/Skills/Pages/ListSkills.php:7
 * @route '/admin/skills'
 */
        ListSkillsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListSkills.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListSkills.form = ListSkillsForm
export default ListSkills