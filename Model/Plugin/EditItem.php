<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Model\Plugin;

use Magento\Framework\View\Element\Template;

class EditItem extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    protected $alias;
    protected $useCache;
    protected $_manageFactory;
    public function __construct(
        Template\Context $context,
        \Magenest\OrderManager\Model\OrderItemFactory $manageFactory,
        array $data = []
    ) {
        $this->_manageFactory = $manageFactory;
        parent::__construct($context, $data);
    }

    public function beforeGetChildHtml($subject,$alias = '', $useCache = true)
    {
        $this->alias=$alias;
        $this->useCache=$useCache;
    }
    public function afterGetChildHtml($subject)
    {
        $_order = $subject->getOrder() ;
        $status = $_order->getStatus();
        $layout = $subject->getLayout();
        if (!$layout) {
            return '';
        }
        $name = $subject->getNameInLayout();
        $out = '';
        if ($this->alias) {
            $childName = $layout->getChildName($name, $this->alias);
            if ($childName) {
                $out = $layout->renderElement($childName, $this->useCache);
            }
        } else {
            foreach ($layout->getChildNames($name) as $child) {
                $out .= $layout->renderElement($child, $this->useCache);
            }
        }
        $result = '<a href="'.$this->getUrl('ordermanager/product/view', ['order_id' => $_order->getId()]).'" class="level-top ui-corner-all">Edit</a>';
        $orderId = $_order->getId();
        $info = $this->_manageFactory->create()->load($orderId,'order_id');
        if($status == "pending" ) {
            if ((empty($info->getData()) || ($info->getStatusCheck() == 'no accept'))) {
                $result = '<a href="' . $this->getUrl('ordermanager/product/view', ['order_id' => $_order->getId()]) . '" class="level-top ui-corner-all">Edit</a>';
            } else {
                $result = '';
            }
        }
        else
        {
            $result = '';
        }

        return $result.$out;
    }
}
