import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/membership',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MembershipController::index
 * @see app/Http/Controllers/MembershipController.php:15
 * @route '/membership'
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
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
export const sample = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: sample.url(options),
    method: 'get',
})

sample.definition = {
    methods: ["get","head"],
    url: '/membership/sample-month',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
sample.url = (options?: RouteQueryOptions) => {
    return sample.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
sample.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: sample.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
sample.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: sample.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
    const sampleForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: sample.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
        sampleForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: sample.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\MembershipController::sample
 * @see app/Http/Controllers/MembershipController.php:47
 * @route '/membership/sample-month'
 */
        sampleForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: sample.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    sample.form = sampleForm
const MembershipController = { index, sample }

export default MembershipController