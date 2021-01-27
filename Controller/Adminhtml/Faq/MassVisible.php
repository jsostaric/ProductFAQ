<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassVisible extends Action
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
     * MassVisible constructor.
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
        $this->faqRepository = $faqRepository;
        $this->filter = $filter;
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

        $collection = $this->filter->getCollection($this->faqCollectionFactory->create());
        try {
            $done = 0;

            foreach ($collection as $item) {
                $item->setIsListed(!$item->getIsListed());
                $this->faqRepository->save($item);
                ++$done;
            }

            if ($done) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) were modified.', $done));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Mass Visible could not be executed');
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
