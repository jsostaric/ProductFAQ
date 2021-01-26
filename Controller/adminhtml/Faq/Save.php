<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;

class Save extends Action
{
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
        $id = $this->getRequest()->getParam('faq_id');

        $questionContent = $this->getRequest()->getParam('question_content');
        $answer = $this->getRequest()->getParam('answer_content');
        if ($id) {
            try {
                $question = $this->faqRepository->getById((int)$id);
                $question->setQuestion($questionContent);
                $question->setAnswerContent($answer);
                $question->save();
            } catch (\Exception $e) {
                throw new CouldNotSaveException(__($e->getMessage()));
            }
        }

        return $this->_redirect('productfaq/faq/');
    }
}
