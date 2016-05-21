<?php
/**
 * Created by PhpStorm.
 */
namespace Magenest\OrderManager\Block\Adminhtml\Address\Billing;

use Psr\Log\LoggerInterface;
use Magento\Directory\Block\Data;
use Magento\Customer\Block\Address\Edit as editData;


/**
 * Class Edit
 * @package Magenest\OrderManager\Block\Adminhtml\Address\Billing
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
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var editData
     */
    protected $_editData;


    /**
     * Edit constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param LoggerInterface $loggerInterface
     * @param \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory
     * @param \Magenest\OrderManager\Model\OrderManageFactory $manageFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param Data $collectionDataShipping
     * @param editData $editData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        LoggerInterface $loggerInterface,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        \Magenest\OrderManager\Model\OrderManageFactory $manageFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        Data  $collectionDataShipping,
        editData $editData,
        array $data =[] )
    {
        $this->_logger         = $loggerInterface;
        $this->_addressFactory = $addressFactory;
        $this->_manageFactory  = $manageFactory;
        $this->_dataBilling = $collectionDataShipping;
        $this->_regionFactory = $regionFactory;
        $this->_editData = $editData;
        parent::__construct($context, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Billing Information '));
    }

    /**
     * @return mixed
     */
    public function getDataBilling(){
        $orderId = $this->getRequest()->getParam('order_id');
     $data = $this->_addressFactory->create()->getCollection()
         ->addFieldToFilter('order_id',$orderId)
         ->addFieldToFilter('address_type','billing');
     return $data;
    }

    /**
     * @return mixed
     */
    public function getRegionId()
    {
        $collection = $this->getDataBilling();
        foreach($collection as $collections)
        {
            $region = $collections->getRegionId();

        }
        return $region;
    }

    /**
     * @return string
     */
    public function getRegionName()
    {
        $id = $this->getRegionId();
        $collection = $this->_regionFactory->create()->load($id,'region_id')->getName();
        return $collection;
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
    public function getUpdateBillingUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/address/updateBilling',['order_id'=>$orderId]);
    }
//    public function getRegionBilling()
//    {
//        $region = $this->_editData->getRegion();
//        return $region;
//    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $id = $this->_manageFactory->create()->load($orderId,'order_id')->getId();
        return $this->getUrl('ordermanager/order/edit',['id'=>$id]);
    }
}