import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
const EditCoupon = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCoupon.url(args, options),
    method: 'get',
})

EditCoupon.definition = {
    methods: ["get","head"],
    url: '/admin/coupons/{record}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
EditCoupon.url = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EditCoupon.definition.url
            .replace('{record}', parsedArgs.record.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
EditCoupon.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EditCoupon.url(args, options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
EditCoupon.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EditCoupon.url(args, options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
    const EditCouponForm = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: EditCoupon.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
        EditCouponForm.get = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditCoupon.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Coupons\Pages\EditCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/EditCoupon.php:7
 * @route '/admin/coupons/{record}/edit'
 */
        EditCouponForm.head = (args: { record: string | number } | [record: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: EditCoupon.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    EditCoupon.form = EditCouponForm
export default EditCoupon