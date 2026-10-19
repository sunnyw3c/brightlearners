import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/account/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\NotificationController::index
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
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
* @see \App\Http\Controllers\Account\NotificationController::markAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
export const markAsRead = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

markAsRead.definition = {
    methods: ["post"],
    url: '/account/notifications/{id}/read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\NotificationController::markAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
markAsRead.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return markAsRead.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\NotificationController::markAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
markAsRead.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\NotificationController::markAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
    const markAsReadForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAsRead.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\NotificationController::markAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
        markAsReadForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAsRead.url(args, options),
            method: 'post',
        })
    
    markAsRead.form = markAsReadForm
/**
* @see \App\Http\Controllers\Account\NotificationController::markAllAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
export const markAllAsRead = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsRead.url(options),
    method: 'post',
})

markAllAsRead.definition = {
    methods: ["post"],
    url: '/account/notifications/mark-all-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\NotificationController::markAllAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
markAllAsRead.url = (options?: RouteQueryOptions) => {
    return markAllAsRead.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\NotificationController::markAllAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
markAllAsRead.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllAsRead.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\NotificationController::markAllAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
    const markAllAsReadForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAllAsRead.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\NotificationController::markAllAsRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
        markAllAsReadForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAllAsRead.url(options),
            method: 'post',
        })
    
    markAllAsRead.form = markAllAsReadForm
const NotificationController = { index, markAsRead, markAllAsRead }

export default NotificationController