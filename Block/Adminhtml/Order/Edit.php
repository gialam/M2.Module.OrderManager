<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Block\Adminhtml\Order;

use Psr\Log\LoggerInterface;
use Magento\Sales\Model\OrderFactory;

/**
 * Class Edit
 * @package Magenest\OrderManager\Block\Adminhtml\Order
 */
class Edit extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magenest\OrderManager\Model\OrderItemFactory
     */
    protected $_itemFactory;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var OrderFactory
     */
    protected $_ordercoreFactory;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var \Magenest\OrderManager\Model\Connector
     */
    protected $_connector;

    protected $_gridFactory;


    public function __construct(
      \Magento\Backend\Block\Template\Context           $context,
      \Magenest\OrderManager\Model\OrderItemFactory     $itemFactory,
      \Magenest\OrderManager\Model\OrderManageFactory   $orderFactory,
      \Magenest\OrderManager\Model\Connector            $connector,
      \Magenest\OrderManager\Model\OrderAddressFactory  $addressFactory,
      \Magento\Directory\Model\RegionFactory            $regionFactory,
      \Magento\Directory\Model\CountryFactory           $countryFactory,
      \Magenest\OrderManager\Helper\Total               $totalInfo,
      \Magenest\OrderManager\Helper\Address             $addressInfo,
      \Magento\Sales\Block\Order\Info\Buttons           $printButton,
      OrderFactory                                      $ordercoreFactory,
      LoggerInterface                                   $loggerInterface,
      array $data =[] )
    {

      $this->_logger           = $loggerInterface;
      $this->_itemFactory      = $itemFactory;
      $this->_orderFactory     = $orderFactory;
      $this->_addressFactory   = $addressFactory;
      $this->_ordercoreFactory = $ordercoreFactory;
      $this->_regionFactory    = $regionFactory;
      $this->_countryFactory   = $countryFactory;
      $this->_connector        = $connector;
      $this->_totalInfo        = $totalInfo;
        $this->_addressInfo    = $addressInfo;
        $this->_printButton    = $printButton;

      parent::__construct($context, $data);
    }

    /**
     * set header name
     */

    public function getOrderId()
    {
        $id = $this->getRequest()->getParam('id');
        $order = $this->_orderFactory->create()->getCollection()->addFieldToFilter('id',$id);
        foreach($order as $orders){
            $orderId = $orders->getOrderId();
            return $orderId;
        }
//        return $orderId;
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
    public function getTotalInfo()
    {
        $orderId = $this->getOrderId();
        if($this->getItemsInfo()->getData()) {
            $data = $this->_totalInfo->getTotalData($orderId);
            return $data;
        }
        else{
            return false;
        }
        }
    /**
     * update quantity
     * @return string
     */
    public function getUpdateProductUrl()
    {
        $orderId = $this->getOrderId();
        return $this->getUrl('ordermanager/order/update',['order_id'=>$orderId]);
    }

    /**
     * @param $itemId
     * @return string
     */
    public function getRemoveProduct($itemId)
    {
        $id = $this->getRequest()->getParam('id');
        return $this->getUrl('ordermanager/order/remove',['id'=>$id,'item_id'=>$itemId]);
    }

    public function getBillingAddress()
    {
        $orderId = $this->getOrderId();
        $check = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id',$orderId)->addFieldToFilter('address_type','billing');
        if($check->getData()) {
        $billing = $this->_addressInfo->getAddress($orderId,'billing');
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
        $check = $this->_addressFactory->create()->getCollection()
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

    /**
     * function add new product for order
     * @return string
     */
    public function getAddProductUrl()
    {
        $orderId = $this->getOrderId();
        return $this->getUrl('ordermanager/item/grid',['order_id'=>$orderId]);
    }
    public function getDeleteDataUrl()
    {
        $orderId = $this->getOrderId();
        return $this->getUrl('ordermanager/order/delete',['order_id'=>$orderId]);
    }

    /**
     * add information to order core
     * @return string
     */
    public function getAcceptUrl()
    {
        $orderId = $this->getOrderId();
        return $this->getUrl('ordermanager/order/accept',['order_id'=>$orderId]);
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

    /**
     * retunr information of billing
     * @param $order_id
     * @return string
     */
    public function getBillingUrl($order_id)
    {
     return $this->getUrl('ordermanager/address/editbilling',['order_id'=>$order_id]);
    }

    /**
     * return information of shipping
     * @param $order_id
     * @return string
     */
    public function getShippingUrl($order_id)
    {
        return $this->getUrl('ordermanager/address/editshipping',['order_id'=>$order_id]);
    }

    public function getEnableRemove()
    {
        $enable = $this->_connector->getRemoveItem();
        return $enable;
    }
    public function getPrintUrl()
    {
        $orderId = $this->getOrderId();
        return $this->getUrl('ordermanager/order/printOrder',['order_id'=>$orderId]);
    }

    public function getBackUrl()
    {
        return $this->getUrl('ordermanager/order/');
    }
    protected function _isAllowed()
    {
        return true;
    }

}