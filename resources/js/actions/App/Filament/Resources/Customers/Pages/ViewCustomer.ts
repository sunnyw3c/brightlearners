import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
const ViewCustomer = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewCustomer.url(args, options),
    method: 'get',
})

ViewCustomer.definition = {
    methods: ["get","head"],
    url: '/admin/customers/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
ViewCustomer.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewCustomer.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
ViewCustomer.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewCustomer.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
ViewCustomer.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewCustomer.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
    const ViewCustomerForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ViewCustomer.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
        ViewCustomerForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ViewCustomer.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Customers\Pages\ViewCustomer::__invoke
 * @see app/Filament/Resources/Customers/Pages/ViewCustomer.php:7
 * @route '/admin/customers/{record}'
 */
        ViewCustomerForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ViewCustomer.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ViewCustomer.form = ViewCustomerForm
export default ViewCustomer