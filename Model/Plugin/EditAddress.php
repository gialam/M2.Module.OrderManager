<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\OrderManager\Model\Plugin;

use Magento\Framework\View\Element\Template;

class EditAddress extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    protected $addresse;
    protected $_addressFactory;


    public function __construct(
        Template\Context $context,
        \Magenest\OrderManager\Model\OrderAddressFactory $addressFactory,
        array $data = []
    )
    {
        $this->_addressFactory = $addressFactory;
        parent::__construct($context, $data);
    }
    public function beforeGetFormattedAddress($subject, $addresse)
    {

        $this->addresse = $addresse;
    }

    public function afterGetFormattedAddress($subject, $result)
    {
        $data = $subject->getOrder();
        $orderId = $data->getId();
        $shipping = $this->_addressFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId)->getData('address_type', 'shippbing');
        $billing = $this->_addressFactory->create()->getCollection()->addFieldToFilter('order_id', $orderId)->getData('address_type', 'billing');
        $type = $this->addresse->getData('address_type');

        if ($type == 'shipping') {
            if (empty($shipping)) {
                if ($data->getStatus() == 'pending') {
                    $urlShipping = '<a href="' . $this->getUrl('ordermanager/address/shipping', ['order_id' => $orderId]) . '" class="level-top ui-corner-all">Edit</a></br>';
                    return $urlShipping . $result;
                }
            } else {
                return $result;
            }
        } else {

            if (empty($billing)) {
                if ($data->getStatus() == 'pending' || $data->getStatus() == 'complete') {
                    $urlBilling = '<a href="' . $this->getUrl('ordermanager/address/billing', ['order_id' => $orderId]) . '" class="level-top ui-corner-all">Edit</a></br>';
                    return $urlBilling . $result;
                }
            } else {
                return $result;
            }
        }

    }
}
