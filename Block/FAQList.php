<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block;

use Inchoo\ProductFAQ\Api\Data\FaqInterface;
use Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterface;
use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;

class FAQList extends Template
{
    protected $faqRepository;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $filterGroupBuilder;

    /**
     * FAQList constructor.
     * @param Template\Context $context
     * @param FaqRepositoryInterface $faqRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        FaqRepositoryInterface $faqRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        array $data = []
    ) {
        $this->faqRepository = $faqRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        parent::__construct($context, $data);
    }

    /**
     * @return FaqInterface|FaqInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFAQList()
    {
        $filters = $this->getFilters();
        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups($filters)->create();

        return $this->faqRepository->getList($searchCriteria)->getItems();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getFilters(): array
    {
        $byProduct = $this->filterBuilder
            ->setField('product_id')
            ->setValue($this->getRequest()->getParam('id'))
            ->setConditionType("eq")->create();
        $product = $this->filterGroupBuilder
            ->addFilter($byProduct)->create();

        $byStore = $this->filterBuilder
            ->setField('store_id')
            ->setValue($this->_storeManager->getStore()->getId())
            ->setConditionType("eq")->create();
        $store = $this->filterGroupBuilder
            ->addFilter($byStore)->create();

        $isListed = $this->filterBuilder
            ->setField('is_listed')
            ->setValue(1)
            ->setConditionType("eq")->create();
        $listed = $this->filterGroupBuilder
            ->addFilter($isListed)->create();

        return [$product, $store, $listed];
    }
}
