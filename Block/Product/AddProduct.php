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
 * Class AddProduct
 * @package Magenest\OrderManager\Block\Product
 */
class AddProduct extends Template
{
    /**
     * @var StoreManagerInterface
     */
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
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Sales\Model\OrderFactory $itemCollectionFactory,
        \Magenest\OrderManager\Model\OrderItemFactory $orderItemFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        StoreManagerInterface $storemanager,
        array $data =[]
    )
    {
        $this->_catalogConfig = $catalogConfig;
        $this->_stockFactory = $stockFactory;
        $this->_storeManager = $storemanager;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $scopeConfig;
        $this->itemCollection        = $itemCollectionFactory;
        $this->_productFactory = $productFactory;
        $this->_orderItemFactory = $orderItemFactory;
        parent::__construct($context, $data);
        //get collection of data
        $attributes = $this->_catalogConfig->getProductAttributes();
        $collection = $this->_productFactory->create()->getCollection();
        $collection->addAttributeToSelect(
            $attributes
        )->addAttributeToSelect(
            'sku'
         );

        $this->setCollection($collection);
        $this->pageConfig->getTitle()->set(__('Add Product(s)'));
    }



    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCollection()) {
            // create pager block for collection
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'webkul.grid.record.pager'
            )->setCollection(
                $this->getCollection() // assign collection to pager
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }
        return $this;
    }

    /**
     * @return string
     */
    // method for get pager html
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
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
     * @return mixed
     */
    public function getImageRender()
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        return $mediaDirectory;
    }

    /**
     * @return \Magento\CatalogInventory\Api\StockStateInterface
     */
    public function getStockProduct()
    {
        $quantity = $this->_stockFactory;
        return $quantity;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSearchBox()
    {
        $searchBox = $this->getLayout()->createBlock('Magenest\OrderManager\Block\Product\Search\SearchBox');
        return $searchBox->toHtml();
    }

    /**
     * @return string
     */
    public function getAddProductUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/addProduct', ['order_id' => $orderId]);
    }
    public function getBackUrl()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        return $this->getUrl('ordermanager/product/view',['order_id'=>$orderId]);
    }

}