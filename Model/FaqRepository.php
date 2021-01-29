<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Model;

use Inchoo\ProductFAQ\Api\Data\FaqInterface;
use Inchoo\ProductFAQ\Api\Data\FaqInterfaceFactory;
use Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterface;
use Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterfaceFactory;
use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class FaqRepository implements FaqRepositoryInterface
{
    /**
     * @var FaqInterfaceFactory
     */
    protected $faqModelFactory;

    /**
     * @var ResourceModel\Faq
     */
    protected $faqResource;

    /**
     * @var ResourceModel\Faq\CollectionFactory
     */
    protected $faqCollectionFactory;

    /**
     * @var FaqSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * FaqRepository constructor.
     * @param FaqInterfaceFactory $faqModelFactory
     * @param FaqSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Inchoo\ProductFAQ\Model\ResourceModel\Faq $faqResource
     * @param CollectionFactory $faqCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        FaqInterfaceFactory $faqModelFactory,
        FaqSearchResultsInterfaceFactory $searchResultsFactory,
        \Inchoo\ProductFAQ\Model\ResourceModel\Faq $faqResource,
        CollectionFactory $faqCollectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->faqModelFactory = $faqModelFactory;
        $this->faqResource = $faqResource;
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $faqId
     * @return FaqInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $faqId): FaqInterface
    {
        $faq = $this->faqModelFactory->create();
        $this->faqResource->load($faq, $faqId);
        if (!$faq->getId()) {
            throw new NoSuchEntityException(__('Question does not exist!', $faqId));
        }

        return $faq;
    }

    /**
     * @param FaqInterface $faq
     * @return FaqInterface
     * @throws CouldNotSaveException
     */
    public function save(FaqInterface $faq): FaqInterface
    {
        try {
            $this->faqResource->save($faq);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $faq;
    }

    /**
     * @param FaqInterface $faq
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(FaqInterface $faq): bool
    {
        try {
            $this->faqResource->delete($faq);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var \Inchoo\ProductFAQ\Model\ResourceModel\Faq\Collection $collection */
        $collection = $this->faqCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
