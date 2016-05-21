<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * @category  Magenest
 */
namespace Magenest\OrderManager\Block\Product\Search;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

class SearchBox extends Template
{
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    protected $_template = 'order/product/search/searchbox.phtml';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productFactory;


    /**
     * SearchBox constructor.
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
     * @param LoggerInterface $loggerInterface
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        LoggerInterface $loggerInterface,
        array $data =[]
    )
    {
        $this->_productFactory   = $productFactory;
        $this->_logger = $loggerInterface;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    public function getDataProduct()
    {
        $data = $this->_productFactory->create()->addAttributeToSelect('*');
        return $data;
    }
//    public function getNameProduct()
//    {
//        $name = $this->getDataProduct()->getName();
////        $this->_logger->addDebug(print_r($name,true));
//        return $name;
//    }


    public function getDataForm()
    {
        $info = $this->getRequest()->getParams();
//        if($info['input-text']) {
//            $textInput = $info['input-text'];
//            $test = $this->getDataProduct();
//            foreach ($test as $tests) {
//                $name = $tests->getName();
//                if (strpos($name, $textInput) !== false) {
//                    $a = $name;
////                    return $a;
//                    $this->_logger->addDebug(print_r($a,true));
//
//                }
//                else{
//                    return false;
//                }
//            }
//
//        }
//        else{
//            return false;
//        }

    }
}


