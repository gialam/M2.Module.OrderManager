<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Block\Adminhtml\Address\Shipping;

use Psr\Log\LoggerInterface;
use Magento\Directory\Block\Data;

/**
 * Class Edit
 * @package Magenest\OrderManager\Block\Adminhtml\Address\Shipping
 */
class Edit extends \Magento\Backend\Block\Template
{

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magenest\OrderManager\Model\OrderAddressFactory
     */
    protected $_addressFactory;

    /**
     * @var Data
     */
    protected $_dataBilling;

    /**
     * @var \Magenest\OrderManager\Model\OrderManageFactory
     */
    protected $_orderFactory;


    /**
     * Edit constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param LoggerInterface $loggerInterface
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $orderFactory
     * @param Data $collectionDataShipping
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        LoggerInterface $loggerInterface,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        \Magenest\OrderManager\Model\OrderManageFactory $orderFactory,
        Data  $collectionDataShipping,
        array $data =[] )
    {
        $this->_logger         = $loggerInterface;
        $this->_addressFactory = $addressFactory;
        $this->_orderFactory   = $orderFactory;
        $this->_dataBilling    = $collectionDataShipping;

        parent::__construct($context, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Shipping Information '));
    }

    /**
     * @return mixed
     */
    public function getDataShipping(){
        $orderId = $this->getRequest()->getParam('order_id');
        $data = $this->_addressFactory->create()->getCollection()
            ->addFieldToFilter('order_id',$orderId)
            ->addFieldToFilter('address_type','shipping');
        return $data;
    }

    /**
     * @return string
     */
    public function getCountryBillingHtmlSelect()
    {
        $country = $this->_dataBilling->getCountryHtmlSelect();
        return $country;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $id = $this->_orderFactory->create()->load($orderId,'order_id')->getId();
        return $this->getUrl('ordermanager/order/edit',['id'=>$id]);
//        return $id;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/address/updateShipping',['order_id'=>$orderId]);
    }
}