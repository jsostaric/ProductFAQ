<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Ui\Component\Form;

use Inchoo\ProductFAQ\Model\ResourceModel\Faq\Collection;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $faqCollectionFactory;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $faqCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $faqCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->faqCollectionFactory = $faqCollectionFactory;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [];
        $dataObject = $this->getCollection()
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem(); // @codingStandardsIgnoreLine - we limit by page size

        if ($dataObject->getFaqId()) {
            $data[$dataObject->getId()] = $dataObject->toArray();
        }

        return $data;
    }

    /**
     * Overrides Abstract Model getCollection() method
     *
     * @return \Inchoo\ProductFAQ\Model\ResourceModel\Faq\Collection
     */
    public function getCollection(): Collection
    {
        if ($this->collection === null) {
            $this->collection = $this->faqCollectionFactory->create();
        }

        return $this->collection;
    }
}
