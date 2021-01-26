<?php

declare(strict_types=1);

namespace Inchoo\ProductFAQ\Api\Data;

interface FaqInterface
{
    const FAQ_ID = 'faq_id';
    const QUESTION = 'question_content';

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @codingStandardsIgnoreLine - id cannot be set to particular type
     * @param mixed $id
     * @return FaqInterface
     */
    public function setId($id): FaqInterface;

    /**
     * @return string|null
     */
    public function getQuestion(): ?string;

    /**
     * @param string $question
     * @return FaqInterface
     */
    public function setQuestion(string $question): FaqInterface;
}
