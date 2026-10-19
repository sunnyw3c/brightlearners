import LearnController from './LearnController'
import ClassController from './ClassController'
import SubjectController from './SubjectController'
import TopicController from './TopicController'
const Learn = {
    LearnController: Object.assign(LearnController, LearnController),
ClassController: Object.assign(ClassController, ClassController),
SubjectController: Object.assign(SubjectController, SubjectController),
TopicController: Object.assign(TopicController, TopicController),
}

export default Learn