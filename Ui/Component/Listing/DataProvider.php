<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Ui\Component\Listing;

use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    protected $collectionFactory;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        \Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
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
     * @return \Inchoo\ProductFAQ\Model\ResourceModel\Faq\Collection|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getCollection()
    {
        if ($this->collection === null) {
            $this->collection = $this->collectionFactory->create();
        }
        return $this->collection;
    }
}
