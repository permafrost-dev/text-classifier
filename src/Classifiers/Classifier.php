<?php

namespace Permafrost\TextClassifier\Classifiers;

use Permafrost\TextClassifier\Tokenizers\Tokenizer;
use Permafrost\TextClassifier\Processors\TextProcessor;

interface Classifier
{
    /**
     * Classify the specified text.
     *
     * @param string $text
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function classify(string $text, ...$parameters);

    /**
     * Initialize the classifier object.
     *
     * @param \Permafrost\TextClassifier\Processors\TextProcessor $processor
     * @param \Permafrost\TextClassifier\Tokenizers\Tokenizer $tokenizer
     *
     * @return mixed
     */
    public function initialize(TextProcessor $processor, Tokenizer $tokenizer): void;

    /**
     * Teach your classifier.
     *
     * @param string $text
     * @param string $category
     *
     * @return \Permafrost\TextClassifier\Classifiers\Classifier
     */
    public function learn(string $text, string $category);

    /**
     * Deserialize from json.
     *
     * @param string $json
     *
     * @return \Permafrost\TextClassifier\Classifiers\Classifier
     */
    public function fromJson(string $json);


    /**
     * Serialize to json.
     *
     * @return string
     */
    public function toJson();
}
