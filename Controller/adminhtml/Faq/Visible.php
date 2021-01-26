<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Controller\Adminhtml\Faq;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;

class Visible extends Action
{
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
        $id = $this->getRequest()->getParam('faq_id');

        if ($id) {
            try {
                $toggleVisibilityOnPage = $this->faqRepository->getById((int)$id);

                $visible = $toggleVisibilityOnPage->getIsListed();

                if (!$visible) {
                    $toggleVisibilityOnPage->setIsListed(1);
                } else {
                    $toggleVisibilityOnPage->setIsListed(0);
                }
                $toggleVisibilityOnPage->save();
            } catch (\Exception $e) {
                throw new CouldNotSaveException(__('Can\'t make changes!'));
            }
        }

        return $this->_redirect('productfaq/faq/');
    }
}
