<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Model\ResourceModel\Faq;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'faq_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Inchoo\ProductFAQ\Model\Faq::class,
            \Inchoo\ProductFAQ\Model\ResourceModel\Faq::class
        );
    }

    /**
     * @return $this
     */
    public function getProductName(): Collection
    {
        $this->getSelect()
            ->join(
                ['product' => 'catalog_product_entity_varchar'],
                'main_table.product_id = product.entity_id',
                'product.value as productName'
            )
            ->group('faq_id');

        return $this;
    }
}
