import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/membership/manage',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
    const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
        showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MembershipManageController::show
 * @see app/Http/Controllers/MembershipManageController.php:22
 * @route '/membership/manage'
 */
        showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\MembershipManageController::cancel
 * @see app/Http/Controllers/MembershipManageController.php:55
 * @route '/membership/manage/cancel'
 */
export const cancel = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancel.url(options),
    method: 'post',
})

cancel.definition = {
    methods: ["post"],
    url: '/membership/manage/cancel',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\MembershipManageController::cancel
 * @see app/Http/Controllers/MembershipManageController.php:55
 * @route '/membership/manage/cancel'
 */
cancel.url = (options?: RouteQueryOptions) => {
    return cancel.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MembershipManageController::cancel
 * @see app/Http/Controllers/MembershipManageController.php:55
 * @route '/membership/manage/cancel'
 */
cancel.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: cancel.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\MembershipManageController::cancel
 * @see app/Http/Controllers/MembershipManageController.php:55
 * @route '/membership/manage/cancel'
 */
    const cancelForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: cancel.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\MembershipManageController::cancel
 * @see app/Http/Controllers/MembershipManageController.php:55
 * @route '/membership/manage/cancel'
 */
        cancelForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: cancel.url(options),
            method: 'post',
        })
    
    cancel.form = cancelForm
const MembershipManageController = { show, cancel }

export default MembershipManageController