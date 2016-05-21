<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Controller\Adminhtml\Address;

use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;

/**
 * Class EditBilling
 * @package Magenest\OrderManager\Controller\Adminhtml\Address
 */
class EditBilling extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * EditBilling constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        LoggerInterface $loggerInterface

    ) {
        $this->_logger      = $loggerInterface;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('order.address.edit.admin')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $this->_view->renderLayout();
    }
    protected function _isAllowed()
    {
        return true;
    }
}

