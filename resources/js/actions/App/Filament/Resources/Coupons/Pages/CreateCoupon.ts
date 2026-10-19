import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
const CreateCoupon = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCoupon.url(options),
    method: 'get',
})

CreateCoupon.definition = {
    methods: ["get","head"],
    url: '/admin/coupons/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
CreateCoupon.url = (options?: RouteQueryOptions) => {
    return CreateCoupon.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
CreateCoupon.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: CreateCoupon.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
CreateCoupon.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: CreateCoupon.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
    const CreateCouponForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: CreateCoupon.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
        CreateCouponForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateCoupon.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Coupons\Pages\CreateCoupon::__invoke
 * @see app/Filament/Resources/Coupons/Pages/CreateCoupon.php:7
 * @route '/admin/coupons/create'
 */
        CreateCouponForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: CreateCoupon.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    CreateCoupon.form = CreateCouponForm
export default CreateCoupon