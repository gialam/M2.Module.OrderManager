<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class View extends Template
{
    protected $_storeManager;
    /**
     * @var CustomerSession\
     */
    protected $_customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $itemCollection;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockFactory;
    /**
     * Edit constructor.
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Sales\Model\OrderFactory $itemCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        \Magento\Sales\Model\OrderFactory $itemCollectionFactory,
        \Magenest\OrderManager\Model\OrderItemFactory $orderItemFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        StoreManagerInterface $storemanager,
        array $data =[]
    )
    {
        $this->_stockFactory = $stockFactory;
        $this->_storeManager = $storemanager;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->itemCollection        = $itemCollectionFactory;
        $this->productFactory = $productFactory;
        $this->_orderItemFactory = $orderItemFactory;
        parent::__construct($context, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__(' Edit Product(s)'));
    }

    /**
     * get data product on store
     * @return $this
     */
    public function getItemsOrder()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $collectionItem = $this->itemCollection->create()->load($orderId);
        return $collectionItem;
    }

    /**
     * get item order edit
     * @return mixed
     */
    public function getNewProduct(){
        $orderId = $this->getRequest()->getParam('order_id');
        $data = $this->_orderItemFactory->create()->getCollection()->addFieldToFilter('order_id',$orderId);
        return $data;
    }

    /**
     * @param $id_product
     * @return string
     */
    public function getRemoveProduct($id_product)
    {
        $orderId = $this->getRequest()->getParam('order_id');
     return $this->getUrl('ordermanager/product/remove',['item_id'=>$id_product,'order_id'=>$orderId]);
    }

    /**
     * @return string
     */
        public function getNewAddProductUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/save',['order_id' => $orderId]);
    }

    /**
     * @return string
     */
    public function getUpdateProductUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/update',['order_id'=>$orderId]);
    }

    /**
     * @return string
     */
    public function getUpdateBackUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/updateBack',['order_id'=>$orderId]);
    }


    /**
     * @return string
     */
        public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * get total order edit
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSubtotalProduct()
    {
        $subtotal = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Product\Subtotal\Total');
        return $subtotal->toHtml();
    }
    public function getCancelUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/cancel',['order_id'=>$orderId]);
    }
    public function getBackUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('sales/order/view',['order_id'=>$orderId]);
    }

    /**
     * return form new product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductNew()
    {
        $newProduct = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Product\NewProduct');
        return $newProduct->toHtml();
    }

    /**
     * get stock product
     * @return \Magento\CatalogInventory\Api\StockStateInterface
     */
    public function getStockProduct()
    {
        $quantity = $this->_stockFactory;
        return $quantity;
    }

    /**
     * @return string
     */
    public function getAddProductUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/addProduct', ['order_id' => $orderId]);
    }

}