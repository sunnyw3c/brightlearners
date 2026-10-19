import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
const ViewProduct = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewProduct.url(args, options),
    method: 'get',
})

ViewProduct.definition = {
    methods: ["get","head"],
    url: '/admin/products/{record}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
ViewProduct.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return ViewProduct.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
ViewProduct.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ViewProduct.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
ViewProduct.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ViewProduct.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
    const ViewProductForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ViewProduct.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
        ViewProductForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ViewProduct.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Products\Pages\ViewProduct::__invoke
 * @see app/Filament/Resources/Products/Pages/ViewProduct.php:7
 * @route '/admin/products/{record}'
 */
        ViewProductForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ViewProduct.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ViewProduct.form = ViewProductForm
export default ViewProduct