<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'Inchoo_ProductFAQ::productfaq';

    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * Update constructor.
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
        $questionContent = $this->getRequest()->getParam('question_content');
        $answer = $this->getRequest()->getParam('answer_content');
        try {
            if ($id = $this->getRequest()->getParam('faq_id')) {
                $question = $this->faqRepository->getById((int)$id);
                $question->setQuestion($questionContent);
                $question->setAnswerContent($answer);
                $this->faqRepository->save($question);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Could not be saved!'));
        }

        return $this->_redirect('productfaq/faq/');
    }
}
