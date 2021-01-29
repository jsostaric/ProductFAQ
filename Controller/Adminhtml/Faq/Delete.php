<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Backend\App\Action;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Inchoo_ProductFAQ::productfaq';

    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param FaqRepositoryInterface $faqRepository
     */
    public function __construct(Action\Context $context, FaqRepositoryInterface $faqRepository)
    {
        parent::__construct($context);
        $this->faqRepository = $faqRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            if ($id = $this->getRequest()->getParam('faq_id')) {
                $question = $this->faqRepository->getById((int)$id);
                $this->faqRepository->delete($question);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Could not delete entry');
        }

        return $this->_redirect('productfaq/faq/');
    }
}
