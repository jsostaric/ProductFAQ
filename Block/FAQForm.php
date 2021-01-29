<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class FAQForm extends Template
{
    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->getUrl(
            'productfaq/product/post',
            [
                'product_id' => $this->getProductId(),
                'store_id' => $this->getStoreId()
            ]
        );
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getStoreId(): int
    {
        return (int)$this->_storeManager->getStore()->getId();
    }

    /**
     * @return mixed
     */
    protected function getProductId()
    {
        return $this->getRequest()->getParam('id');
    }
}
