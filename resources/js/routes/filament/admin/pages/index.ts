import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
export const analyticsDashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analyticsDashboard.url(options),
    method: 'get',
})

analyticsDashboard.definition = {
    methods: ["get","head"],
    url: '/admin/analytics-dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
analyticsDashboard.url = (options?: RouteQueryOptions) => {
    return analyticsDashboard.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
analyticsDashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analyticsDashboard.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
analyticsDashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: analyticsDashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
    const analyticsDashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: analyticsDashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
        analyticsDashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analyticsDashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Analytics\AnalyticsDashboard::__invoke
 * @see app/Filament/Pages/Analytics/AnalyticsDashboard.php:7
 * @route '/admin/analytics-dashboard'
 */
        analyticsDashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analyticsDashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    analyticsDashboard.form = analyticsDashboardForm
/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
export const pilotCohort = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pilotCohort.url(options),
    method: 'get',
})

pilotCohort.definition = {
    methods: ["get","head"],
    url: '/admin/pilot-cohort',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
pilotCohort.url = (options?: RouteQueryOptions) => {
    return pilotCohort.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
pilotCohort.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pilotCohort.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
pilotCohort.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pilotCohort.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
    const pilotCohortForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pilotCohort.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
        pilotCohortForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pilotCohort.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Analytics\PilotCohort::__invoke
 * @see app/Filament/Pages/Analytics/PilotCohort.php:7
 * @route '/admin/pilot-cohort'
 */
        pilotCohortForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pilotCohort.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pilotCohort.form = pilotCohortForm
/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
export const correctionQueue = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: correctionQueue.url(options),
    method: 'get',
})

correctionQueue.definition = {
    methods: ["get","head"],
    url: '/admin/correction-queue',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
correctionQueue.url = (options?: RouteQueryOptions) => {
    return correctionQueue.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
correctionQueue.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: correctionQueue.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
correctionQueue.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: correctionQueue.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
    const correctionQueueForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: correctionQueue.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
        correctionQueueForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: correctionQueue.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Content\CorrectionQueue::__invoke
 * @see app/Filament/Pages/Content/CorrectionQueue.php:7
 * @route '/admin/correction-queue'
 */
        correctionQueueForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: correctionQueue.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    correctionQueue.form = correctionQueueForm
/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
export const overdueReviews = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: overdueReviews.url(options),
    method: 'get',
})

overdueReviews.definition = {
    methods: ["get","head"],
    url: '/admin/overdue-reviews',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
overdueReviews.url = (options?: RouteQueryOptions) => {
    return overdueReviews.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
overdueReviews.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: overdueReviews.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
overdueReviews.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: overdueReviews.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
    const overdueReviewsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: overdueReviews.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
        overdueReviewsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: overdueReviews.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Content\OverdueReviews::__invoke
 * @see app/Filament/Pages/Content/OverdueReviews.php:7
 * @route '/admin/overdue-reviews'
 */
        overdueReviewsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: overdueReviews.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    overdueReviews.form = overdueReviewsForm
/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
export const reviewQueue = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reviewQueue.url(options),
    method: 'get',
})

reviewQueue.definition = {
    methods: ["get","head"],
    url: '/admin/review-queue',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
reviewQueue.url = (options?: RouteQueryOptions) => {
    return reviewQueue.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
reviewQueue.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reviewQueue.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
reviewQueue.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reviewQueue.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
    const reviewQueueForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: reviewQueue.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
        reviewQueueForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reviewQueue.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Content\ReviewQueue::__invoke
 * @see app/Filament/Pages/Content/ReviewQueue.php:7
 * @route '/admin/review-queue'
 */
        reviewQueueForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reviewQueue.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    reviewQueue.form = reviewQueueForm
/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
export const notificationTesting = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notificationTesting.url(options),
    method: 'get',
})

notificationTesting.definition = {
    methods: ["get","head"],
    url: '/admin/notification-testing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
notificationTesting.url = (options?: RouteQueryOptions) => {
    return notificationTesting.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
notificationTesting.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notificationTesting.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
notificationTesting.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notificationTesting.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
    const notificationTestingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: notificationTesting.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
        notificationTestingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notificationTesting.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Support\NotificationTesting::__invoke
 * @see app/Filament/Pages/Support/NotificationTesting.php:7
 * @route '/admin/notification-testing'
 */
        notificationTestingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notificationTesting.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    notificationTesting.form = notificationTestingForm
/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
export const progressTroubleshooting = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: progressTroubleshooting.url(options),
    method: 'get',
})

progressTroubleshooting.definition = {
    methods: ["get","head"],
    url: '/admin/progress-troubleshooting',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
progressTroubleshooting.url = (options?: RouteQueryOptions) => {
    return progressTroubleshooting.definition.url + queryParams(options)
}

/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
progressTroubleshooting.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: progressTroubleshooting.url(options),
    method: 'get',
})
/**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
progressTroubleshooting.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: progressTroubleshooting.url(options),
    method: 'head',
})

    /**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
    const progressTroubleshootingForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: progressTroubleshooting.url(options),
        method: 'get',
    })

            /**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
        progressTroubleshootingForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: progressTroubleshooting.url(options),
            method: 'get',
        })
            /**
* @see \App\Filament\Pages\Support\ProgressTroubleshooting::__invoke
 * @see app/Filament/Pages/Support/ProgressTroubleshooting.php:7
 * @route '/admin/progress-troubleshooting'
 */
        progressTroubleshootingForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: progressTroubleshooting.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    progressTroubleshooting.form = progressTroubleshootingForm
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \Filament\Pages\Dashboard::__invoke
 * @see vendor/filament/filament/src/Pages/Dashboard.php:7
 * @route '/admin'
 */
        dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
const pages = {
    analyticsDashboard: Object.assign(analyticsDashboard, analyticsDashboard),
pilotCohort: Object.assign(pilotCohort, pilotCohort),
correctionQueue: Object.assign(correctionQueue, correctionQueue),
overdueReviews: Object.assign(overdueReviews, overdueReviews),
reviewQueue: Object.assign(reviewQueue, reviewQueue),
notificationTesting: Object.assign(notificationTesting, notificationTesting),
progressTroubleshooting: Object.assign(progressTroubleshooting, progressTroubleshooting),
dashboard: Object.assign(dashboard, dashboard),
}

export default pages