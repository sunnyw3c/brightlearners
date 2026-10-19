import DashboardController from './DashboardController'
import LibraryController from './LibraryController'
import LearningProfileController from './LearningProfileController'
import WeeklyLearningController from './WeeklyLearningController'
import OrderController from './OrderController'
import MembershipController from './MembershipController'
import ProgressController from './ProgressController'
import AccountAssessmentController from './AccountAssessmentController'
import RecommendationController from './RecommendationController'
import NotificationController from './NotificationController'
const Account = {
    DashboardController: Object.assign(DashboardController, DashboardController),
LibraryController: Object.assign(LibraryController, LibraryController),
LearningProfileController: Object.assign(LearningProfileController, LearningProfileController),
WeeklyLearningController: Object.assign(WeeklyLearningController, WeeklyLearningController),
OrderController: Object.assign(OrderController, OrderController),
MembershipController: Object.assign(MembershipController, MembershipController),
ProgressController: Object.assign(ProgressController, ProgressController),
AccountAssessmentController: Object.assign(AccountAssessmentController, AccountAssessmentController),
RecommendationController: Object.assign(RecommendationController, RecommendationController),
NotificationController: Object.assign(NotificationController, NotificationController),
}

export default Account