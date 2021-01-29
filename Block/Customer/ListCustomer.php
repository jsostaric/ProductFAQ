<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block\Customer;

use Inchoo\ProductFAQ\Model\ResourceModel\Faq\Collection;
use Inchoo\ProductFAQ\Model\ResourceModel\Faq\CollectionFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;

class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CollectionFactory
     */
    protected $faqCollectionFactory;

    /**
     * ListCustomer constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param ProductRepository $productRepository
     * @param CollectionFactory $faqCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $customerSession, // @codingStandardsIgnoreLine - implemented proxy for session
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        ProductRepository $productRepository,
        CollectionFactory $faqCollectionFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->productRepository = $productRepository;
        $this->faqCollectionFactory = $faqCollectionFactory;
    }

    /**
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getQuestions(): Collection
    {
        $collection = $this->faqCollectionFactory->create();
        $collection->addFieldToFilter('user_id', $this->customerSession->getCustomerId());
        $collection->getProductName();

        return $collection;
    }

    /**
     * @param string $date
     * @return string
     */
    public function dateFormat(string $date): string
    {
        return $this->formatDate($date, \IntlDateFormatter::SHORT);
    }

    /**
     * @param string $productId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductUrl(string $productId): string
    {
        $product = $this->productRepository->getById($productId);

        return $product->getProductUrl();
    }
}
