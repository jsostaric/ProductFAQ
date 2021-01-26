<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Ui\Component\Listing;

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
    public function getData()
    {
        $data = $this->getCollection()->toArray();
        foreach ($data['items'] as &$item) {
            if (!$item['answer_content']) {
                $item['has_answer'] = 0;
            } else {
                $item['has_answer'] = 1;
            }
        }

        return $data;
    }

    /**
     * overrides Abstract Model getCollection
     *
     * @return \Inchoo\ProductFAQ\Model\ResourceModel\Faq\Collection
     */
    public function getCollection()
    {
        if ($this->collection === null) {
            $this->collection = $this->faqCollectionFactory->create();
        }
        return $this->collection;
    }
}
