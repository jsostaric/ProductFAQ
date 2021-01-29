<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block;

use Inchoo\ProductFAQ\Api\Data\FaqInterface;
use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;

class FAQList extends Template
{
    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var FilterGroupBuilder
     */
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
    public function getFAQList(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('is_listed', 1)
            ->addFilter('product_id', $this->getRequest()->getParam('id'))
            ->addFilter('store_id', $this->_storeManager->getStore()->getId())
            ->create();

        return $this->faqRepository->getList($searchCriteria)->getItems();
    }
}
