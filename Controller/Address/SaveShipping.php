<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Controller\Address;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Customer\Model\Session as CustomerSession;


/**
 * Class Save
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class SaveShipping extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_time;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var CustomerSession
     */
    protected $_customerSession;
    /**
     * Save constructor.
     * @param Context $context
     * @param RequestInterface $request
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        LoggerInterface $loggerInterface,
        OrderFactory $orderFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $timeCreate,
        CustomerSession $customerSession
    ) {

        $this->_time = $timeCreate;
        $this->_customerSession = $customerSession;
        $this->_request = $request;
        $this->_logger = $loggerInterface;
        $this->_orderFactory   = $orderFactory;
        parent::__construct($context);

    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $order = $this->getRequest()->getParam('order_id');
        $customerId = $this->_customerSession->getCustomerId();
        $time = $this->_time->gmtDate();
        $orderCollection = $this->_orderFactory->create()->load($order);
        $status = $orderCollection->getStatus();
        $firstName  = $orderCollection->getCustomerFirstname();
        $lastName   = $orderCollection->getCustomerLastname();
        $email = $orderCollection->getCustomerEmail();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $modelOrder = $this->_objectManager->create('Magenest\OrderManager\Model\OrderManage');
        $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderAddress');
        $shippingId = $data['shippingid'];

        if ($shippingId) {

            $modelOrder->load($order,'order_id');
            $dataOrder = [
                'order_id'        => $order,
                'customer_id' =>$customerId,
                'status'          => $status,
                'status_check'=>'checking',
                'customer_name'   =>  $firstName.' '.$lastName,
                'customer_email'  => $email,
                'create_at' =>$time,
            ];
            $modelOrder->addData($dataOrder);

            $model->load($shippingId, 'address_id');
            $dataInfo = [
                'address_id'     =>$shippingId,
                'order_id'       =>$order,
                'firstname'      => $data['firstname'],
                'lastname'       => $data['lastname'],
                'company'        => $data['company'],
                'telephone'      => $data['telephone'],
                'fax'            => $data['fax'],
                'street'         => $data['street'],
                'city'           => $data['city'],
                'postcode'       => $data['postcode'],
                'region_id'      => $data['region_id'],
                'country_id'     => $data['country_id'],
                'address_type'   =>'shipping'
            ];
            $model->addData($dataInfo);
            try {
                $modelOrder->save();
                $model->save();
                $this->messageManager->addSuccess(__('Shipping address has been sent to admin !'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back'))
                {
                    return $resultRedirect->setPath('sales/order/view',
                        ['order_id' => $this->getRequest()->getParam('order_id')]);
                }

                return $resultRedirect->setPath('sales/order/view',
                    ['order_id' => $this->getRequest()->getParam('order_id')]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());

            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while saving the shipping address.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        return $resultRedirect->setPath('sales/order/view',['order_id' => $this->getRequest()->getParam('order_id')]);
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */

    protected function _isAllowed()
    {
        return true;
    }
}