import ListProducts from './ListProducts'
import CreateProduct from './CreateProduct'
import ViewProduct from './ViewProduct'
import EditProduct from './EditProduct'
const Pages = {
    ListProducts: Object.assign(ListProducts, ListProducts),
CreateProduct: Object.assign(CreateProduct, CreateProduct),
ViewProduct: Object.assign(ViewProduct, ViewProduct),
EditProduct: Object.assign(EditProduct, EditProduct),
}

export default Pages