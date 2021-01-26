<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Customer;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;

class Index extends Action
{
    /**
     * Index constructor.
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('productfaq/customer');
        }
        if ($block = $resultPage->getLayout()->getBlock('productfaq_customer_list')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }

        $resultPage->getConfig()->getTitle()->set(__('My Product Questions'));
        return $resultPage;
    }
}
