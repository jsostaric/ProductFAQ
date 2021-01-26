<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block\Customer;

use Inchoo\ProductFAQ\Api\FaqRepositoryInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
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
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ListCustomer constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param ProductRepository $productRepository
     * @param FaqRepositoryInterface $faqRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession, // @codingStandardsIgnoreLine - implemented proxy for session
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        FaqRepositoryInterface $faqRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepository $productRepository,
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
        $this->faqRepository = $faqRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Inchoo\ProductFAQ\Api\Data\FaqInterface|\Inchoo\ProductFAQ\Api\Data\FaqInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getQuestions()
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('user_id', $this->getUserId())->create();

        return $this->faqRepository->getList($searchCriteria)->getItems();
    }

    /**
     * @param string $date
     * @return string
     */
    public function dateFormat(string $date)
    {
        return $this->formatDate($date, \IntlDateFormatter::SHORT);
    }

    /**
     * @param string $productId
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductUrl(string $productId)
    {
        $product = $this->getProduct($productId);

        return $product->getProductUrl();
    }

    /**
     * @param string $productId
     * @return string|null
     */
    public function getProductName(string $productId)
    {
        $product = $this->getProduct($productId);
        return $product->getName();
    }

    /**
     * @param string $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getProduct(string $productId)
    {
        return $this->productRepository->getById($productId);
    }

    /**
     * @return int
     */
    protected function getUserId(): int
    {
        return (int)$this->customerSession->getId();
    }
}
