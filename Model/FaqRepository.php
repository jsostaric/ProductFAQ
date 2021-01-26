<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Model;

use Inchoo\ProductFAQ\Api\Data;
use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class FaqRepository implements FaqRepositoryInterface
{
    protected $faqModelFactory;
    protected $faqResource;
    protected $faqCollectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;

    /**
     * FaqRepository constructor.
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterfaceFactory $faqModelFactory
     * @param \Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterfaceFactory $searchResultsFactory
     * @param \Inchoo\ProductFAQ\Model\ResourceModel\Faq $faqResource
     * @param \Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory $faqCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        \Inchoo\ProductFAQ\Api\Data\FaqInterfaceFactory $faqModelFactory,
        \Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterfaceFactory $searchResultsFactory,
        \Inchoo\ProductFAQ\Model\ResourceModel\Faq $faqResource,
        \Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory $faqCollectionFactory,
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
     * @return \Inchoo\ProductFAQ\Api\Data\FaqInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $faqId)
    {
        $faq = $this->faqModelFactory->create();
        $this->faqResource->load($faq, $faqId);
        if (!$faq->getId()) {
            throw new NoSuchEntityException(__('Question does not exist!', $faqId));
        }

        return $faq;
    }

    /**
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterface $faq
     * @return bool|\Inchoo\ProductFAQ\Api\Data\FaqInterface
     * @throws CouldNotSaveException
     */
    public function save(\Inchoo\ProductFAQ\Api\Data\FaqInterface $faq)
    {
        try {
            $this->faqResource->save($faq);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return true;
    }

    /**
     * @param \Inchoo\ProductFAQ\Api\Data\FaqInterface $faq
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\Inchoo\ProductFAQ\Api\Data\FaqInterface $faq)
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
     * @return \Inchoo\ProductFAQ\Api\Data\FaqSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
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
