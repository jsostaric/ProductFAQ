<?php

namespace Inchoo\ProductFAQ\Api\Data;

interface FaqInterface
{
    const FAQ_ID = 'faq_id';
    const QUESTION = 'question_content';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @codingStandardsIgnoreLine - id cannot be set to particular type
     * @param mixed $id
     * @return FaqInterface
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getQuestion();

    /**
     * @param string $question
     * @return FaqInterface
     */
    public function setQuestion(string $question);
}
