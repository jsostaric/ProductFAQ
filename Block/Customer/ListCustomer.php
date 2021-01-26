<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Block\Customer;

use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Registry;

class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
{
    protected $faqRegistry;
    protected $productRepository;

    /**
     * ListCustomer constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param Registry $faqRegistry
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession, // @codingStandardsIgnoreLine - implemented proxy for session
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        Registry $faqRegistry,
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
        $this->faqRegistry = $faqRegistry;
        $this->productRepository = $productRepository;
    }

    /**
     * @return mixed|null
     */
    public function getQuestions()
    {
        return $this->faqRegistry->registry('inchoo_product_faq');
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
}
