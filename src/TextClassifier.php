<?php

namespace Permafrost\TextClassifier;

use Permafrost\TextClassifier\Pipelines\TextTokenizingPipeline;
use Permafrost\TextClassifier\Tokenizers\Tokenizer;
use Permafrost\TextClassifier\Classifiers\Classifier;
use Permafrost\TextClassifier\Processors\TextProcessor;
use Permafrost\TextClassifier\Pipelines\TextProcessingPipeline;

class TextClassifier
{
    /** @var \Permafrost\TextClassifier\Classifiers\Classifier $classifier */
    public $classifier;

    /** @var \Permafrost\TextClassifier\Pipelines\TextProcessingPipeline $processor */
    protected $processor;

    /** @var \Permafrost\TextClassifier\Pipelines\TextTokenizingPipeline $tokenizer */
    protected $tokenizer;

    public function __construct($processor, $tokenizer, Classifier $classifier)
    {
        if ($processor instanceof TextProcessor) {
            $processor = [$processor];
        }

        if (is_array($processor)) {
            $processor = new TextProcessingPipeline($processor);
        }

        if ($tokenizer instanceof Tokenizer) {
            $tokenizer = [$tokenizer];
        }

        if (is_array($tokenizer)) {
            $tokenizer = new TextTokenizingPipeline($tokenizer);
        }

        $this->processor = $processor;
        $this->tokenizer = $tokenizer;
        $this->classifier = $classifier;

        $this->initialize();
    }

    protected function initialize(): void
    {
        $this->classifier->initialize($this->processor, $this->tokenizer);
    }

    protected function loadTrainingData(string $filename): array
    {
        $data = trim(file_get_contents($filename));
        $lines = explode(PHP_EOL, $data);

        $result = [];

        foreach ($lines as $line) {
            if (trim($line) === '' || substr($line, 0, 2) === '--') {
                //skip comments and empty lines
                continue;
            }

            [$category,$text] = explode('|', $line, 2);
            if ($category === '--') {
                //skip comments
                continue;
            }

            $text = mb_convert_encoding($text, 'ascii');
            $result[] = [$category, $text];
        }

        return $result;
    }

    /**
     * Train using content from a file, which should be a text file in the format:
     *      category A|sample text
     *      category B|some other text
     *      ...
     */
    public function trainFromFile(string $filename): self
    {
        $trainingData = $this->loadTrainingData($filename);

        return $this->train($trainingData);
    }

    /**
     * Trains the model.
     *
     * $items should be an array of strings in the format:
     *      ['category a|sample text abc', 'category b|my text def']
     */
    public function train(array $items): self
    {
        foreach ($items as $item) {
            if (is_string($item)) {
                [$category, $text] = explode('|', $item, 2);
            } else {
                [$category, $text] = $item;
            }

            $this->classifier->learn($text, $category);
        }

        return $this;
    }

    public function test(string $filename)
    {
        $testData = trim(file_get_contents($filename));
        $lines = explode(PHP_EOL, $testData);

        $result = [];

        foreach ($lines as $line) {
            $result[] = $this->classify($line) . '|' . $line;
        }

        print_r($result);

        return $this;
    }

    /**
     * Return the most likely category for $text.
     *
     * @return int|string|null
     */
    public function classify(string $text)
    {
        return $this->classifier->classify($text);
    }

    /**
     * Classify $text and return the top N most likely categories.
     *
     * @return array
     */
    public function classifyMulti(string $text, int $categoryCount = 2)
    {
        return $this->classifier->categorizeMulti($text, $categoryCount);
    }

    /**
     * Save the trained model to a file.
     */
    public function saveTrained(string $filename): bool
    {
        $data = $this->classifier->toJson();

        return file_put_contents($filename, $data) !== false;
    }

    /**
     * Load a trained model previously saved to file.
     */
    public function loadTrained(string $filename): self
    {
        if (!file_exists($filename) || !is_file($filename)) {
            return false;
        }

        $this->classifier->fromJson(file_get_contents($filename));

        return $this;
    }
}
