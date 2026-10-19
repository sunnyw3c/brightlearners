import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
const ListCoupons = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCoupons.url(options),
    method: 'get',
})

ListCoupons.definition = {
    methods: ["get","head"],
    url: '/admin/coupons',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
ListCoupons.url = (options?: RouteQueryOptions) => {
    return ListCoupons.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
ListCoupons.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ListCoupons.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
ListCoupons.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ListCoupons.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
    const ListCouponsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ListCoupons.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
        ListCouponsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListCoupons.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Resources\Coupons\Pages\ListCoupons::__invoke
 * @see app/Filament/Resources/Coupons/Pages/ListCoupons.php:7
 * @route '/admin/coupons'
 */
        ListCouponsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ListCoupons.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ListCoupons.form = ListCouponsForm
export default ListCoupons