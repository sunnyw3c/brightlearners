import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
const CreateLearningProgram = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLearningProgram.url(options),
    method: 'get',
})

CreateLearningProgram.definition = {
    methods: ["get","head"],
    url: '/admin/learning-programs/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
CreateLearningProgram.url = (options?: RouteQueryOptions) => {
    return CreateLearningProgram.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
CreateLearningProgram.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateLearningProgram.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
CreateLearningProgram.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateLearningProgram.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
    const CreateLearningProgramForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateLearningProgram.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
        CreateLearningProgramForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateLearningProgram.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\LearningPrograms\Pages\CreateLearningProgram::__invoke
 * @see app/Filament/Resources/LearningPrograms/Pages/CreateLearningProgram.php:7
 * @route '/admin/learning-programs/create'
 */
        CreateLearningProgramForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateLearningProgram.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateLearningProgram.form = CreateLearningProgramForm
export default CreateLearningProgram