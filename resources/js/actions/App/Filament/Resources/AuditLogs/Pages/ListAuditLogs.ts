import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
const ListAuditLogs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAuditLogs.url(options),
    method: 'get',
})

ListAuditLogs.definition = {
    methods: ["get","head"],
    url: '/admin/audit-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
ListAuditLogs.url = (options?: RouteQueryOptions) => {
    return ListAuditLogs.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
ListAuditLogs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListAuditLogs.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
ListAuditLogs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListAuditLogs.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
    const ListAuditLogsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListAuditLogs.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
        ListAuditLogsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListAuditLogs.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\AuditLogs\Pages\ListAuditLogs::__invoke
 * @see app/Filament/Resources/AuditLogs/Pages/ListAuditLogs.php:7
 * @route '/admin/audit-logs'
 */
        ListAuditLogsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListAuditLogs.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListAuditLogs.form = ListAuditLogsForm
export default ListAuditLogs