<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;

class MassInVisible extends Action
{
    public const ADMIN_RESOURCE = 'Inchoo_PoductFAQ::productfaq';

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
     * MassInVisible constructor.
     * @param Action\Context $context
     * @param CollectionFactory $faqCollectionFactory
     * @param FaqRepositoryInterface $faqRepository
     * @param Filter $filter
     */
    public function __construct(
        Action\Context $context,
        CollectionFactory $faqCollectionFactory,
        FaqRepositoryInterface $faqRepository,
        Filter $filter
    ) {
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->faqRepository = $faqRepository;
        $this->filter = $filter;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $collection = $this->filter->getCollection($this->faqCollectionFactory->create());

            $done = 0;

            foreach ($collection->getItems() as $item) {
                $item->setIsListed(0);
                $this->faqRepository->save($item);
                ++$done;
            }

            if ($done) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) were modified.', $done));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Mass Invisible could not be executed');
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
