import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/sitemap.xml',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\SitemapController::index
 * @see app/Http/Controllers/SitemapController.php:13
 * @route '/sitemap.xml'
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
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
export const section = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: section.url(args, options),
    method: 'get',
})

section.definition = {
    methods: ["get","head"],
    url: '/sitemaps/{section}.xml',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
section.url = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { section: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        section: args.section,
                }

    return section.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
section.get = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: section.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
section.head = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: section.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
    const sectionForm = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: section.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
        sectionForm.get = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: section.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\SitemapController::section
 * @see app/Http/Controllers/SitemapController.php:23
 * @route '/sitemaps/{section}.xml'
 */
        sectionForm.head = (args: { section: string | number } | [section: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: section.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    section.form = sectionForm
const sitemap = {
    index: Object.assign(index, index),
section: Object.assign(section, section),
}

export default sitemap