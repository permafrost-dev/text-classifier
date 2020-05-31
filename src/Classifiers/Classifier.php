<?php

namespace Permafrost\TextClassifier\Classifiers;

use Permafrost\TextClassifier\Pipelines\TextProcessingPipeline;
use Permafrost\TextClassifier\Pipelines\TextTokenizingPipeline;

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
     * @param \Permafrost\TextClassifier\Pipelines\TextProcessingPipeline $processor
     * @param \Permafrost\TextClassifier\Pipelines\TextTokenizingPipeline $tokenizer
     *
     * @return mixed
     */
    public function initialize(TextProcessingPipeline $processor, TextTokenizingPipeline $tokenizer): void;

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
