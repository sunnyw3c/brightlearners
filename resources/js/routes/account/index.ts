import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import profiles from './profiles'
import library from './library'
import orders from './orders'
import progressFb9fab from './progress'
import assessments287135 from './assessments'
import recommendations8e64db from './recommendations'
import notifications1ce82a from './notifications'
/**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/account/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\DashboardController::dashboard
 * @see app/Http/Controllers/Account/DashboardController.php:22
 * @route '/account/dashboard'
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
/**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
export const membership = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: membership.url(options),
    method: 'get',
})

membership.definition = {
    methods: ["get","head"],
    url: '/account/membership',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
membership.url = (options?: RouteQueryOptions) => {
    return membership.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
membership.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: membership.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
membership.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: membership.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
    const membershipForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: membership.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
        membershipForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: membership.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\MembershipController::membership
 * @see app/Http/Controllers/Account/MembershipController.php:13
 * @route '/account/membership'
 */
        membershipForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: membership.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    membership.form = membershipForm
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
export const weeklyLearning = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: weeklyLearning.url(options),
    method: 'get',
})

weeklyLearning.definition = {
    methods: ["get","head"],
    url: '/account/weekly-learning',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
weeklyLearning.url = (options?: RouteQueryOptions) => {
    return weeklyLearning.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
weeklyLearning.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: weeklyLearning.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
weeklyLearning.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: weeklyLearning.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
    const weeklyLearningForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: weeklyLearning.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
        weeklyLearningForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: weeklyLearning.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\WeeklyLearningController::weeklyLearning
 * @see app/Http/Controllers/Account/WeeklyLearningController.php:19
 * @route '/account/weekly-learning'
 */
        weeklyLearningForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: weeklyLearning.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    weeklyLearning.form = weeklyLearningForm
/**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
export const progress = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: progress.url(options),
    method: 'get',
})

progress.definition = {
    methods: ["get","head"],
    url: '/account/progress',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
progress.url = (options?: RouteQueryOptions) => {
    return progress.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
progress.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: progress.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
progress.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: progress.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
    const progressForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: progress.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
        progressForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: progress.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\ProgressController::progress
 * @see app/Http/Controllers/Account/ProgressController.php:19
 * @route '/account/progress'
 */
        progressForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: progress.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    progress.form = progressForm
/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
export const assessments = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assessments.url(options),
    method: 'get',
})

assessments.definition = {
    methods: ["get","head"],
    url: '/account/assessments',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
assessments.url = (options?: RouteQueryOptions) => {
    return assessments.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
assessments.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assessments.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
assessments.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assessments.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
    const assessmentsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: assessments.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
        assessmentsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: assessments.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\AccountAssessmentController::assessments
 * @see app/Http/Controllers/Account/AccountAssessmentController.php:17
 * @route '/account/assessments'
 */
        assessmentsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: assessments.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    assessments.form = assessmentsForm
/**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
export const recommendations = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: recommendations.url(options),
    method: 'get',
})

recommendations.definition = {
    methods: ["get","head"],
    url: '/account/recommendations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
recommendations.url = (options?: RouteQueryOptions) => {
    return recommendations.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
recommendations.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: recommendations.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
recommendations.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: recommendations.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
    const recommendationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: recommendations.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
        recommendationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: recommendations.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\RecommendationController::recommendations
 * @see app/Http/Controllers/Account/RecommendationController.php:24
 * @route '/account/recommendations'
 */
        recommendationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: recommendations.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    recommendations.form = recommendationsForm
/**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
export const notifications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})

notifications.definition = {
    methods: ["get","head"],
    url: '/account/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
notifications.url = (options?: RouteQueryOptions) => {
    return notifications.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
notifications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
notifications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notifications.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
    const notificationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: notifications.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
        notificationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notifications.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Account\NotificationController::notifications
 * @see app/Http/Controllers/Account/NotificationController.php:13
 * @route '/account/notifications'
 */
        notificationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notifications.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    notifications.form = notificationsForm
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
export const settings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})

settings.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/account/settings',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.url = (options?: RouteQueryOptions) => {
    return settings.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settings.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: settings.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: settings.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: settings.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: settings.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
settings.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: settings.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
    const settingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: settings.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: settings.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: settings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: settings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: settings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/account/settings'
 */
        settingsForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    settings.form = settingsForm
const account = {
    dashboard: Object.assign(dashboard, dashboard),
profiles: Object.assign(profiles, profiles),
library: Object.assign(library, library),
orders: Object.assign(orders, orders),
membership: Object.assign(membership, membership),
weeklyLearning: Object.assign(weeklyLearning, weeklyLearning),
progress: Object.assign(progress, progressFb9fab),
assessments: Object.assign(assessments, assessments287135),
recommendations: Object.assign(recommendations, recommendations8e64db),
notifications: Object.assign(notifications, notifications1ce82a),
settings: Object.assign(settings, settings),
}

export default account