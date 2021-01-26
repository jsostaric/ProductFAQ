<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;

class Visible extends Action
{
    public const ADMIN_RESOURCE = 'Inchoo_ProductFAQ::productfaq';

    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * Visible constructor.
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
     * @throws CouldNotSaveException
     */
    public function execute()
    {
        try {
            if ($id = $this->getRequest()->getParam('faq_id')) {
                $question = $this->faqRepository->getById((int)$id);

                $isListed = !$question->getIsListed();
                $question->setIsListed((int)$isListed);
                $this->faqRepository->save($question);
            }
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Can\'t make changes!'));
        }

        return $this->_redirect('productfaq/faq/');
    }
}
