<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Model;

use Inchoo\ProductFAQ\Api\Data\FaqInterface;

class Faq extends \Magento\Framework\Model\AbstractModel implements FaqInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Inchoo\ProductFAQ\Model\ResourceModel\Faq::class);
    }

    /**
     * @codingStandardsIgnoreLine - id cannot be set to particular return type
     * @return mixed
     */
    public function getId()
    {
        return $this->getData(self::FAQ_ID);
    }

    /**
     * @codingStandardsIgnoreLine - id cannot be set to particular type
     * @param mixed $id
     * @return FaqInterface
     */
    public function setId($id): FaqInterface
    {
        return $this->setData(self::FAQ_ID, $id);
    }

    /**
     * @return string
     */
    public function getQuestion(): ?string
    {
        return $this->getData(self::QUESTION);
    }

    /**
     * @param string $question
     * @return FaqInterface
     */
    public function setQuestion(string $question): FaqInterface
    {
        return $this->setData(self::QUESTION, $question);
    }
}
