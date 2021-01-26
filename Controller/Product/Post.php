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
            $question_content = $this->getRequest()->getParam('question_field');
            $userId = (int)$this->customerSession->getId();
            $productId = (int)$this->getRequest()->getParam('id');
            $storeId = (int)$this->getRequest()->getParam('storeId');

            $question = $this->faqModelFactory->create();
            $question->setQuestion($question_content);
            $question->setStoreId($storeId);
            $question->setProductId($productId);
            $question->setUserId($userId);
            $this->faqRepository->save($question);

            $this->_eventManager->dispatch('inchoo_faq_notification', ['question' => $question]);

            $this->messageManager->addSuccessMessage('Thank you for your Question.');
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
