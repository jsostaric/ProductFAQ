<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Inchoo\ProductFAQ\Model\Faq;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;

class MassInvisible extends Action
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
     * MassInvisible constructor.
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
        $this->faqRepository = $faqRepository;
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $collection = $this->filter->getCollection($this->faqCollectionFactory->create());
        try {
            $done = 0;

            foreach ($collection as $item) {
                $this->setInvisible($item);

                ++$done;
            }

            if ($done) {
                $this->messageManager->addSuccess(__('A total of %1 record(s) were modified.', $done));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Mass set Invisible could not be executed');
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }

    /**
     * @param Faq $item
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function setInvisible(Faq $item): void
    {
        $item->setIsListed(0);
        $this->faqRepository->save($item);
    }
}
