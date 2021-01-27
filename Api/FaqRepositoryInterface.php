<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Api;

use Inchoo\ProductFAQ\Api\Data\FaqInterface;
use Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterface;
use Inchoo\ProductFAQ\Model\Faq;
use Magento\Framework\Api\SearchCriteriaInterface;

interface FaqRepositoryInterface
{
    /**
     * @param int $faqId
     * @return \Inchoo\ProductFAQ\Api\Data\FaqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $faqId): FaqInterface;

    /**
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterface $faq
     * @return \Inchoo\ProductFAQ\Api\Data\FaqInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\FaqInterface $faq): FaqInterface;

    /**
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterface $faq
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\FaqInterface $faq): bool;

    /**
     * Retrieve news matching the specified search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
