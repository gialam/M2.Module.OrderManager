<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magenest\OrderManager\Model\OrderManageFactory ;
use Magenest\OrderManager\Model\OrderItemFactory ;
use Magenest\OrderManager\Helper\Address ;
use Magenest\OrderManager\Helper\Total ;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\OrderFactory;

/**
 * Class History
 *
 * @package Magenest\Ticket\Block\Order
 */
class ViewManage extends Template
{
    /**
     * @var OrderManageFactory
     */
    protected $_ordermanageFactory;

    /**
     * @var OrderItemFactory
     */
    protected $_itemFactory;
    /**
     * @var Address
     */
    protected $_addressInfo;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var string
     */
    protected $_totalInfo;

    /**
     * ViewManage constructor.
     * @param Context $context
     * @param OrderManageFactory $ordermanageFactory
     * @param OrderItemFactory $itemFactory
     * @param Address $addressInfo
     * @param CustomerSession $customerSession
     * @param OrderFactory $ordercoreFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Total $totalInfo
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $orderAddressFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        OrderManageFactory $ordermanageFactory,
        OrderItemFactory   $itemFactory,
        Address            $addressInfo,
        CustomerSession $customerSession,
        OrderFactory $ordercoreFactory,
        ScopeConfigInterface $scopeConfig,
        Total $totalInfo,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magenest\OrderManager\Model\OrderAddressFactory $orderAddressFactory,
        array $data = []
    ) {

        $this->_totalInfo = $totalInfo;
        $this->_regionFactory    = $regionFactory;
        $this->_ordermanageFactory = $ordermanageFactory;
        $this->_itemFactory        = $itemFactory;
        $this->_addressInfo     = $addressInfo;
        $this->_ordercoreFactory = $ordercoreFactory;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_orderAddressFactory = $orderAddressFactory;

        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Information'));
    }

    /**
     * Get Ticket Collection
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrderId()
    {
       $orderId = $this->getRequest()->getParam('order_id');

        return $orderId;
    }
    /**
     *  infomation product of order
     */
    public function getItemsInfo()
    {
        $orderId = $this->getOrderId();
        $items   = $this->_itemFactory->create()->getCollection()->addFieldToFilter('order_id',$orderId);
        return $items;
    }
    /**
     * currency
     * @return string
     */
    public function getSymbolItem()
    {
        $orderId = $this->getOrderId();
        $data = $this->_ordercoreFactory->create()->load($orderId);
        $symbol = $data->getOrderCurrency()->getCurrencySymbol();
        return $symbol;
    }
    public function getBillingAddress()
    {
        $orderId = $this->getOrderId();
        $check = $this->_orderAddressFactory->create()->getCollection()
            ->addFieldToFilter('order_id',$orderId)->addFieldToFilter('address_type','billing');
        if($check->getData()) {
            $billing = $this->_addressInfo->getAddress($orderId, 'billing');
            return $billing;
        }
        else
        {
            return false;
        }
    }

    public function getShippingAddress()
    {
        $orderId = $this->getOrderId();
        $check = $this->_orderAddressFactory->create()->getCollection()
            ->addFieldToFilter('order_id',$orderId)->addFieldToFilter('address_type','shipping');
        if($check->getData()) {
        $shipping = $this->_addressInfo->getAddress($orderId,'shipping');
        return $shipping;
        }
        else
        {
            return false;
        }
    }
     public function getTotalInfo()
     {
         if($this->getItemsInfo()->getData()) {

             $orderId = $this->getOrderId();
             $data = $this->_totalInfo->getTotalData($orderId);
             return $data;
         }
         else{
             return false;
         }
     }
    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param object $ticket
     * @return string
     */
    public function getViewUrl($orderId)
    {
        return $this->getUrl('ordermanager/order/view', ['order_id' => $orderId]);
    }

}
