import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\DashboardController::switchMethod
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
export const switchMethod = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: switchMethod.url(args, options),
    method: 'post',
})

switchMethod.definition = {
    methods: ["post"],
    url: '/account/profiles/{profile}/switch',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\DashboardController::switchMethod
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
switchMethod.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { profile: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { profile: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    profile: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        profile: typeof args.profile === 'object'
                ? args.profile.id
                : args.profile,
                }

    return switchMethod.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\DashboardController::switchMethod
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
switchMethod.post = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: switchMethod.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\DashboardController::switchMethod
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
    const switchMethodForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: switchMethod.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\DashboardController::switchMethod
 * @see app/Http/Controllers/Account/DashboardController.php:214
 * @route '/account/profiles/{profile}/switch'
 */
        switchMethodForm.post = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: switchMethod.url(args, options),
            method: 'post',
        })
    
    switchMethod.form = switchMethodForm
/**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/profiles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\LearningProfileController::index
 * @see app/Http/Controllers/Account/LearningProfileController.php:21
 * @route '/account/profiles'
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
* @see \App\Http\Controllers\Account\LearningProfileController::store
 * @see app/Http/Controllers/Account/LearningProfileController.php:39
 * @route '/account/profiles'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/account/profiles',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\LearningProfileController::store
 * @see app/Http/Controllers/Account/LearningProfileController.php:39
 * @route '/account/profiles'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\LearningProfileController::store
 * @see app/Http/Controllers/Account/LearningProfileController.php:39
 * @route '/account/profiles'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\LearningProfileController::store
 * @see app/Http/Controllers/Account/LearningProfileController.php:39
 * @route '/account/profiles'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\LearningProfileController::store
 * @see app/Http/Controllers/Account/LearningProfileController.php:39
 * @route '/account/profiles'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Account\LearningProfileController::update
 * @see app/Http/Controllers/Account/LearningProfileController.php:51
 * @route '/account/profiles/{profile}'
 */
export const update = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/account/profiles/{profile}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Account\LearningProfileController::update
 * @see app/Http/Controllers/Account/LearningProfileController.php:51
 * @route '/account/profiles/{profile}'
 */
update.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { profile: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { profile: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    profile: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        profile: typeof args.profile === 'object'
                ? args.profile.id
                : args.profile,
                }

    return update.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\LearningProfileController::update
 * @see app/Http/Controllers/Account/LearningProfileController.php:51
 * @route '/account/profiles/{profile}'
 */
update.patch = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Account\LearningProfileController::update
 * @see app/Http/Controllers/Account/LearningProfileController.php:51
 * @route '/account/profiles/{profile}'
 */
    const updateForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\LearningProfileController::update
 * @see app/Http/Controllers/Account/LearningProfileController.php:51
 * @route '/account/profiles/{profile}'
 */
        updateForm.patch = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Account\LearningProfileController::destroy
 * @see app/Http/Controllers/Account/LearningProfileController.php:63
 * @route '/account/profiles/{profile}'
 */
export const destroy = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/account/profiles/{profile}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Account\LearningProfileController::destroy
 * @see app/Http/Controllers/Account/LearningProfileController.php:63
 * @route '/account/profiles/{profile}'
 */
destroy.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { profile: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { profile: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    profile: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        profile: typeof args.profile === 'object'
                ? args.profile.id
                : args.profile,
                }

    return destroy.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\LearningProfileController::destroy
 * @see app/Http/Controllers/Account/LearningProfileController.php:63
 * @route '/account/profiles/{profile}'
 */
destroy.delete = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Account\LearningProfileController::destroy
 * @see app/Http/Controllers/Account/LearningProfileController.php:63
 * @route '/account/profiles/{profile}'
 */
    const destroyForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\LearningProfileController::destroy
 * @see app/Http/Controllers/Account/LearningProfileController.php:63
 * @route '/account/profiles/{profile}'
 */
        destroyForm.delete = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
export const weeklyLearning = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: weeklyLearning.url(args, options),
    method: 'get',
})

weeklyLearning.definition = {
    methods: ["get","head"],
    url: '/account/profiles/{profile}/weekly-learning',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
weeklyLearning.url = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { profile: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { profile: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    profile: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        profile: typeof args.profile === 'object'
                ? args.profile.id
                : args.profile,
                }

    return weeklyLearning.definition.url
            .replace('{profile}', parsedArgs.profile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
weeklyLearning.get = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: weeklyLearning.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
weeklyLearning.head = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: weeklyLearning.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
    const weeklyLearningForm = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: weeklyLearning.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
        weeklyLearningForm.get = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: weeklyLearning.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:113
 * @route '/account/profiles/{profile}/weekly-learning'
 */
        weeklyLearningForm.head = (args: { profile: number | { id: number } } | [profile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: weeklyLearning.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    weeklyLearning.form = weeklyLearningForm
const profiles = {
    switch: Object.assign(switchMethod, switchMethod),
index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
weeklyLearning: Object.assign(weeklyLearning, weeklyLearning),
}

export default profiles