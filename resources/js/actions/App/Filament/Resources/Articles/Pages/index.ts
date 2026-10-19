import ListArticles from './ListArticles'
import CreateArticle from './CreateArticle'
import EditArticle from './EditArticle'
const Pages = {
    ListArticles: Object.assign(ListArticles, ListArticles),
CreateArticle: Object.assign(CreateArticle, CreateArticle),
EditArticle: Object.assign(EditArticle, EditArticle),
}

export default Pages