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


/**
 * Class NewProduct
 * @package Magenest\OrderManager\Block\Product
 */
class NewProduct extends Template
{
    /**
     * @var string
     */
    protected $_template = 'order/product/newproduct.phtml';

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

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
     * NewProduct constructor.
     * @param Context $context
     * @param \Magento\Sales\Model\OrderFactory $itemCollectionFactory
     * @param \Magenest\OrderManager\Model\OrderItemFactory $orderItemFactory
     * @param StoreManagerInterface $storemanager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\OrderFactory $itemCollectionFactory,
        \Magenest\OrderManager\Model\OrderItemFactory $orderItemFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        StoreManagerInterface $storemanager,
        array $data =[]
    )
    {
        $this->_stockFactory = $stockFactory;
        $this->_storeManager         = $storemanager;
        $this->itemCollection        = $itemCollectionFactory;
        $this->_orderItemFactory     = $orderItemFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getSymbolCurrency()
    {
        $orderId        = $this->getRequest()->getParam('order_id');
        $collectionItem = $this->itemCollection->create()->load($orderId);
        $symbol         = $collectionItem->getOrderCurrency()->getCurrencySymbol();
        return $symbol;
    }

    /**
     * @return mixed
     */
    public function getNewProduct(){
        $orderId = $this->getRequest()->getParam('order_id');
        $data    = $this->_orderItemFactory->create()->getCollection()->addFieldToFilter('order_id',$orderId);
        return $data;
    }

    /**
     * @param $id
     * @return string
     */
    public function getRemoveProduct($id)
    {
        $orderId   = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/remove',['item_id'=>$id,'order_id'=>$orderId]);
    }

    /**
     * @return string
     */
    public function getUpdateQuantityUrl()
    {
        $orderId   = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/updateItem',['order_id'=>$orderId]);
    }

    /**
     * @return \Magento\CatalogInventory\Api\StockStateInterface
     */
    public function getStockProduct()
    {
        $quantity = $this->_stockFactory;
        return $quantity;
    }
}