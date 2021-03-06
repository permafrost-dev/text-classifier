<?php
/**
 * Naive-Bayes Classifier
 * The original class was taken from niiknow/bayes.
 *
 * @see https://github.com/niiknow/bayes
 *
 * @license MIT
 */

namespace Permafrost\TextClassifier\Classifiers;

use Permafrost\TextClassifier\Pipelines\BasicPipeline;
use Permafrost\TextClassifier\Pipelines\TextProcessingPipeline;
use Permafrost\TextClassifier\Pipelines\TextTokenizingPipeline;

class NaiveBayes implements Classifier
{
    /**
     * @var array
     */
    public $STATE_KEYS = [
        'categories', 'docCount',
        'totalDocuments',
        'vocabulary', 'vocabularySize',
        'wordCount', 'wordFrequencyCount',
    ];

    /**
     * hashmap of our category names.
     *
     * @var array
     */
    public $categories;

    /**
     * document frequency table for each of our categories
     *  for each category, how often were documents mapped to it.
     *
     * @var number
     */
    public $docCount;

    /**
     * number of documents we have learned from.
     *
     * @var number
     */
    public $totalDocuments;

    /**
     * Vocabulary list.
     *
     * @var array
     */
    public $vocabulary;

    /**
     * Vocabulary size.
     *
     * @var number
     */
    public $vocabularySize;

    /**
     * for each category, how many words total were mapped to it.
     *
     * @var number
     */
    public $wordCount;

    /**
     * word frequency table for each category
     *  for each category, how frequent was a given word mapped to it.
     *
     * @var number
     */
    public $wordFrequencyCount;

    /**
     * constructor options which include tokenizer.
     *
     * @var array
     */
    protected $options;

    /**
     * the tokenizer function.
     *
     * @var function
     */
    protected $tokenizer;

    /** @var \Permafrost\TextClassifier\Pipelines\TextProcessingPipeline $processingPipeline */
    protected $processingPipeline;

    /** @var \Permafrost\TextClassifier\Pipelines\TextTokenizingPipeline $tokenizer */
    protected $tokenizerPipeline;

    /**
     * Initialize an instance of a Naive-Bayes Classifier.
     */
    public function __construct()
    {
        // set options object
    }

    public function initialize(TextProcessingPipeline $processor, TextTokenizingPipeline $tokenizer, array $options = []): void
    {
        $this->processingPipeline = $processor;
        $this->tokenizerPipeline = $tokenizer;
        $this->options = $options;

        if (!isset($this->options['tokenizer'])) {
            $this->options['tokenizer'] = function ($text, $mode) {
                $text = $this->processingPipeline->run($text, $mode);

                return $this->tokenizerPipeline->run($text, $mode);
            };
        }

        $this->reset();
    }

    /**
     * Identify the category of the provided text parameter.
     *
     * @param string $text
     *
     * @return string the category or null
     */
    public function categorize($text)
    {
        $that = $this;
        $maxProbability = -INF;
        $chosenCategory = null;

        if ($that->totalDocuments > 0) {
            $probabilities = $that->probabilities($text, BasicPipeline::MODE_CLASSIFY);

            // iterate thru our categories to find the one with max probability
            // for this text
            foreach ($probabilities as $category=>$logProbability) {
                if ($logProbability > $maxProbability) {
                    $maxProbability = $logProbability;
                    $chosenCategory = $category;
                }
            }
        }

        return $chosenCategory;
    }

    public function classify(string $text, ...$parameters)
    {
        return $this->categorize($text);
    }

    /**
     * Build a frequency hashmap where
     *  - the keys are the entries in `tokens`
     *  - the values are the frequency of each entry in `tokens`.
     *
     * @param array $tokens array of string
     *
     * @return array hashmap of token frequency
     */
    public function frequencyTable($tokens): array
    {
        $frequencyTable = [];
        foreach ($tokens as $token) {
            if (!isset($frequencyTable[$token])) {
                $frequencyTable[$token] = 1;
            } else {
                ++$frequencyTable[$token];
            }
        }

        return $frequencyTable;
    }

    /**
     * Make sure the category exists in dictionary.
     *
     * @param string $categoryName
     *
     * @return NaiveBayes
     */
    public function initializeCategory($categoryName)
    {
        if (!isset($this->categories[$categoryName])) {
            $this->docCount[$categoryName] = 0;
            $this->wordCount[$categoryName] = 0;
            $this->wordFrequencyCount[$categoryName] = [];
            $this->categories[$categoryName] = true;
        }

        return $this;
    }

    /**
     * Teach your classifier.
     *
     * @return NaiveBayes
     */
    public function learn(string $text, string $category)
    {
        $that = $this;

        // initialize category data structures if we've never seen this category
        $that->initializeCategory($category);

        // update our count of how many documents mapped to this category
        ++$that->docCount[$category];

        // update the total number of documents we have learned from
        ++$that->totalDocuments;

        // normalize the text into a word array
        $tokens = ($that->tokenizer)($text, BasicPipeline::MODE_TRAIN);

        // get a frequency count for each token in the text
        $frequencyTable = $that->frequencyTable($tokens);

        // Update vocabulary and word frequency count for this category
        foreach ($frequencyTable as $token=>$frequencyInText) {
            // add this word to our vocabulary if not already existing
            if (!isset($that->vocabulary[$token])) {
                $that->vocabulary[$token] = true;
                ++$that->vocabularySize;
            }

            // update the frequency information for this word in this category
            if (!isset($that->wordFrequencyCount[$category][$token])) {
                $that->wordFrequencyCount[$category][$token] = $frequencyInText;
            } else {
                $that->wordFrequencyCount[$category][$token] += $frequencyInText;
            }

            // update the count of all words we have seen mapped to this category
            $that->wordCount[$category] += $frequencyInText;
        }

        return $that;
    }

    /**
     * Extract the probabilities for each known category.
     *
     * @param string $text
     * @param string $mode
     *
     * @return array probabilities by category or null
     */
    public function probabilities($text, $mode = BasicPipeline::MODE_UNSPECIFIED): array
    {
        $that = $this;
        $probabilities = [];

        if ($that->totalDocuments > 0) {
            $tokens = ($that->tokenizer)($text, $mode);
            $frequencyTable = $that->frequencyTable($tokens);

            // for this text
            // iterate thru our categories to find the one with max probability
            foreach ($that->categories as $category=>$value) {
                $categoryProbability = $that->docCount[$category] / $that->totalDocuments;
                $logProbability = log($categoryProbability);
                foreach ($frequencyTable as $token=>$frequencyInText) {
                    $tokenProbability = $that->tokenProbability($token, $category);

                    // determine the log of the P( w | c ) for this word
                    $logProbability += $frequencyInText * log($tokenProbability);
                }

                $probabilities[$category] = $logProbability;
            }
        }

        return $probabilities;
    }

    public function categorizeMulti(string $text, int $categoryCount = 3): array
    {
        $probabilities = $this->probabilities($text, BasicPipeline::MODE_CLASSIFY);

        arsort($probabilities);

        return array_slice($probabilities, 0, $categoryCount, true);
    }

    /**
     * Reset the bayes class.
     *
     * @return NaiveBayes
     */
    public function reset()
    {
        if (!$this->options) {
            $this->options = [];
        }

        // set default tokenizer
        $this->tokenizer = function ($text) {
            // convert everything to lowercase
            $text = mb_strtolower($text);

            // split the words
            preg_match_all('/[[:alpha:]]+/u', $text, $matches);

            // first match list of words

            return $matches[0];
        };

        if (isset($this->options['tokenizer'])) {
            $this->tokenizer = $this->options['tokenizer'];
        }

        $this->categories = [];
        $this->docCount = [];
        $this->totalDocuments = 0;
        $this->vocabulary = [];
        $this->vocabularySize = 0;
        $this->wordCount = [];
        $this->wordFrequencyCount = [];

        return $this;
    }

    /**
     * Serialize to json.
     *
     * @return string the json string
     */
    public function toJson()
    {
        $result = [];

        // serialize to json
        foreach ($this->STATE_KEYS as $k) {
            $result[$k] = $this->{$k};
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_BIGINT_AS_STRING);
    }

    /**
     * Deserialize from json.
     *
     * @param object $json string or array
     *
     * @return NaiveBayes
     */
    public function fromJson(string $json)
    {
        $result = $json;

        // deserialize from json
        if (is_string($json)) {
            $result = json_decode($json, true);
        }

        $this->reset();

        // deserialize from json
        foreach ($this->STATE_KEYS as $k) {
            if (isset($result[$k])) {
                $this->{$k} = $result[$k];
            }
        }

        return $this;
    }

    /**
     * Calculate the probability that a `token` belongs to a `category`.
     *
     * @param string $token
     * @param string $category
     *
     * @return number the probability
     */
    public function tokenProbability($token, $category)
    {
        // how many times this word has occurred in documents mapped to this category
        $wordFrequencyCount = 0;
        if (isset($this->wordFrequencyCount[$category][$token])) {
            $wordFrequencyCount = $this->wordFrequencyCount[$category][$token];
        }

        // what is the count of all words that have ever been mapped to this category
        $wordCount = $this->wordCount[$category];

        // use laplace Add-1 Smoothing equation

        return ($wordFrequencyCount + 1) / ($wordCount + $this->vocabularySize);
    }
}
