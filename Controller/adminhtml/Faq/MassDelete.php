<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $faqCollectionFactory
     */
    public function __construct(Action\Context $context, Filter $filter, CollectionFactory $faqCollectionFactory)
    {
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $collection = $this->filter->getCollection($this->faqCollectionFactory->create());
            $done = 0;
            foreach ($collection as $item) {
                $this->deleteItem($item);
                ++$done;
            }

            if ($done) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) were modified.', $done));
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage()));
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }

    /**
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterface $item
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    protected function deleteItem(\Inchoo\ProductFAQ\Api\Data\FaqInterface $item)
    {
        $item->delete();
    }
}
