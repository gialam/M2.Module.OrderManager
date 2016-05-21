<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.

 */
namespace Magenest\OrderManager\Controller\Adminhtml\Order;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Backend\App\Action;
use Magenest\OrderManager\Helper\Pdf;
use Psr\Log\LoggerInterface;

/**
 * Class PdforderPrint
 * @package Magenest\PDFInvoice\Controller\Adminhtml\Order
 */
class PrintOrder extends Action
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;


    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Pdforder
     */
    protected $_dataTemplate;

    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * PdforderPrint constructor.
     * @param Context $context
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param CustomerSession $customerSession
     * @param PageFactory $resultPageFactory
     * @param Pdforder $dataTemplate
     */
    public function __construct(
        Context $context,
        DateTime $dateTime,
        FileFactory $fileFactory,
        CustomerSession $customerSession,
        PageFactory $resultPageFactory,
        Pdf $dataTemplate,
        LoggerInterface $loggerInterface
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->_dataTemplate = $dataTemplate;
        $this->_logger = $loggerInterface;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Exception
     */
    public function printOrder()
    {
        $orderId = $this->getRequest()->getParam('order_id');

        if ($orderId) {
            return $this->fileFactory->create(
                sprintf('order%s.pdf', $this->dateTime->date('Y-m-d_H-i-s')),
                $this->_dataTemplate->getPrintOrder($orderId),
                DirectoryList::VAR_DIR,
                'application/pdf'
            );
        }
    }
    public function execute()
    {
        $data = $this->printOrder()->getData();
//        $this->_logger->addDEbug(print_r($data,true));
        return true;
    }

    /* @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
