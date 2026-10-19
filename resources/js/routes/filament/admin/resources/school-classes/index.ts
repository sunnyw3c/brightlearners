import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/school-classes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/ListSchoolClasses.php:7
 * @route '/admin/school-classes'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/school-classes/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/CreateSchoolClass.php:7
 * @route '/admin/school-classes/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
export const edit = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/school-classes/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
edit.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
edit.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
edit.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
    const editForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
        editForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass::__invoke
 * @see app/Filament/Resources/SchoolClasses/Pages/EditSchoolClass.php:7
 * @route '/admin/school-classes/{record}/edit'
 */
        editForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
const schoolClasses = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
edit: Object.assign(edit, edit),
}

export default schoolClasses