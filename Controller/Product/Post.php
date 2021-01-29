<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Product;

use Inchoo\ProductFAQ\Api\Data\FaqInterfaceFactory;
use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class Post extends Action implements HttpPostActionInterface
{
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var FaqInterfaceFactory
     */
    protected $faqModelFactory;

    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * Post constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param FaqRepositoryInterface $faqRepository
     * @param FaqInterfaceFactory $faqModelFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        FaqRepositoryInterface $faqRepository,
        FaqInterfaceFactory $faqModelFactory
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->faqRepository = $faqRepository;
        $this->faqModelFactory = $faqModelFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws CouldNotSaveException
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage('You need to login before you submit your question');
            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        try {
            /** @var array $data */
            $data = $this->getRequest()->getParams();
            $userId = (int)$this->customerSession->getId();

            $message = $this->validateData($data);

            if ($message) {
                $this->messageManager->addErrorMessage(__($message));
                return $this->_redirect($this->_redirect->getRefererUrl());
            }

            $question = $this->faqModelFactory->create();
            $question->setProductId((int)$data['product_id']);
            $question->setStoreId((int)$data['store_id']);
            $question->setUserId($userId);
            $question->setQuestion($data['question_field']);
            $this->faqRepository->save($question);

            $this->_eventManager->dispatch('inchoo_faq_notification', ['question' => $question]);

            $this->messageManager->addSuccessMessage('Thank you for your Question.');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Question could not be saved'));
            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validateData(array $data): array
    {
        $message = [];

        if (!$data['product_id']) {
            $message[] = 'Product can not be found';
        }

        if (!$data['store_id']) {
            $message[] = 'Store view is not found';
        }

        if (!trim($data['question_field'])) {
            $message[] = 'Question field cannot be empty';
        }

        return $message;
    }
}
