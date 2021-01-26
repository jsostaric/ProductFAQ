<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Inchoo_ProductFAQ::productfaq';

    /**
     * @var CollectionFactory
     */
    protected $faqCollectionFactory;

    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $faqCollectionFactory
     * @param FaqRepositoryInterface $faqRepository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $faqCollectionFactory,
        FaqRepositoryInterface $faqRepository
    ) {
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->filter = $filter;
        $this->faqRepository = $faqRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
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
                $this->faqRepository->delete($item);
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
}
