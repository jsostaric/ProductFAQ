<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Action
{
    public const ADMIN_RESOURCE = 'Inchoo_ProductFAQ::productfaq';

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Inchoo_ProductFAQ::productfaq');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit FAQ'));

        return $resultPage;
    }
}
