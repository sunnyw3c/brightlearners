import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Account\NotificationController::read
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
export const read = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: read.url(args, options),
    method: 'post',
})

read.definition = {
    methods: ["post"],
    url: '/account/notifications/{id}/read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\NotificationController::read
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
read.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return read.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\NotificationController::read
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
read.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: read.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\NotificationController::read
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
    const readForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: read.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\NotificationController::read
 * @see app/Http/Controllers/Account/NotificationController.php:37
 * @route '/account/notifications/{id}/read'
 */
        readForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: read.url(args, options),
            method: 'post',
        })
    
    read.form = readForm
/**
* @see \App\Http\Controllers\Account\NotificationController::markAllRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
export const markAllRead = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllRead.url(options),
    method: 'post',
})

markAllRead.definition = {
    methods: ["post"],
    url: '/account/notifications/mark-all-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Account\NotificationController::markAllRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
markAllRead.url = (options?: RouteQueryOptions) => {
    return markAllRead.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\NotificationController::markAllRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
markAllRead.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllRead.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Account\NotificationController::markAllRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
    const markAllReadForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAllRead.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Account\NotificationController::markAllRead
 * @see app/Http/Controllers/Account/NotificationController.php:45
 * @route '/account/notifications/mark-all-read'
 */
        markAllReadForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAllRead.url(options),
            method: 'post',
        })
    
    markAllRead.form = markAllReadForm
const notifications = {
    read: Object.assign(read, read),
markAllRead: Object.assign(markAllRead, markAllRead),
}

export default notifications