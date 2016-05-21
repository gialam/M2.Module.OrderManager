<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Controller\Address;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Shipping
 * @package Magenest\OrderManager\Controller\Address
 */
class Shipping extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var
     */
    protected $_coreRegistry;

    /**
     * @var
     */
    protected $editshipping;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        $this->_resultPageFactory = $pageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $orderId = $this->getRequest()->getParams();
        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('order.shipping.edit')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $this->_view->renderLayout();
    }
    protected function _isAllowed()
    {
        return true;
    }
}
