import CartController from './CartController'
import CheckoutController from './CheckoutController'
import OrderResultController from './OrderResultController'
const Commerce = {
    CartController: Object.assign(CartController, CartController),
CheckoutController: Object.assign(CheckoutController, CheckoutController),
OrderResultController: Object.assign(OrderResultController, OrderResultController),
}

export default Commerce