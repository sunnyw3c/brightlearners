import ListSubjects from './ListSubjects'
import CreateSubject from './CreateSubject'
import EditSubject from './EditSubject'
const Pages = {
    ListSubjects: Object.assign(ListSubjects, ListSubjects),
CreateSubject: Object.assign(CreateSubject, CreateSubject),
EditSubject: Object.assign(EditSubject, EditSubject),
}

export default Pages