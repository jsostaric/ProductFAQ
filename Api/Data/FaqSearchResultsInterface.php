<?php

namespace Inchoo\ProductFAQ\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface FaqSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Inchoo\ProductFAQ\Api\Data\FaqInterface[]
     */
    public function getItems();

    /**
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
