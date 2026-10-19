import WebhookController from './WebhookController'
import PaymentController from './PaymentController'
const Payments = {
    WebhookController: Object.assign(WebhookController, WebhookController),
PaymentController: Object.assign(PaymentController, PaymentController),
}

export default Payments