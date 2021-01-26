<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class FAQForm extends Template
{
    protected $_storeManager;

    /**
     * FAQForm constructor.
     * @param Template\Context $context
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(Template\Context $context, StoreManagerInterface $storeManager, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl(
            'productfaq/product/post',
            [
                'id' => $this->getProductId(),
                'storeId' => $this->getStoreId()
            ]
        );
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return mixed
     */
    protected function getProductId()
    {
        return $this->getRequest()->getParam('id');
    }
}
