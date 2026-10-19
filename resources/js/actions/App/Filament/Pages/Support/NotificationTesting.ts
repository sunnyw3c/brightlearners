import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
const NotificationTesting = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: NotificationTesting.url(options),
    method: 'get',
})

NotificationTesting.definition = {
    methods: ["get","head"],
    url: '/admin/notification-testing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
NotificationTesting.url = (options?: RouteQueryOptions) => {
    return NotificationTesting.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
NotificationTesting.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: NotificationTesting.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
NotificationTesting.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: NotificationTesting.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
    const NotificationTestingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: NotificationTesting.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
        NotificationTestingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: NotificationTesting.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
        NotificationTestingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: NotificationTesting.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    NotificationTesting.form = NotificationTestingForm
export default NotificationTesting