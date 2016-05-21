<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Save
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Invoice
 */
class Remove extends \Magento\Framework\App\Action\Action
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Save constructor.
     * @param Context $context
     * @param RequestInterface $request
     */
    public function __construct(
        Context $context,
        RequestInterface $request
    ) {

        $this->_request = $request;
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
        $orderId = $data['order_id'];
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $data['item_id'];
            $model = $this->_objectManager->create('Magenest\OrderManager\Model\OrderItem');
            $modelData =  $model->load($id,'id');

            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $modelData->setData($id);
                $modelData->delete();
                $this->messageManager->addSuccess(__('Product have deleted .'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('ordermanager/product/view',['order_id'=>$orderId]);
                }
                return $resultRedirect->setPath('ordermanager/product/view',['order_id'=>$orderId]);
            } catch (\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());

            } catch (\Exception $e) {
                $this->messageManager->addError($e, __('Something went wrong while remove product'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('ordermanager/product/view',['order_id'=>$orderId]);
            }
        }
        return $resultRedirect->setPath('ordermanager/product/view',['order_id'=>$orderId]);
    }
    protected function _isAllowed()
    {
        return true;
    }
}
