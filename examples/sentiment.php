<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Skyeng\Lemmatizer;
use Permafrost\TextClassifier\TextClassifier;
use Permafrost\TextClassifier\Classifiers\NaiveBayes;
use Permafrost\TextClassifier\Processors\TextLemmatizer;
use Permafrost\TextClassifier\Tokenizers\BasicTokenizer;
use Permafrost\TextClassifier\Tokenizers\NGramTokenizer;
use Permafrost\TextClassifier\Processors\StopwordRemover;
use Permafrost\TextClassifier\Processors\BasicTextNormalizer;
use Permafrost\TextClassifier\Pipelines\TextProcessingPipeline;

//Use different processors for training and classifying.  Since we're using keyword tokens, add all lemmas for each token
//during training to increase the size of the training data.  When classifying, let's remove stopwords in addition
//to basic text normalization, because we'll be processing phrases.
//The TextLemmatizer uses a single instance of the lemmatizer class to avoid creating an object and all of its
//data every time a token is processed.
$trainingProcessors = [new TextLemmatizer(new Lemmatizer()), new BasicTextNormalizer()];
$classifyProcessors = [new StopwordRemover(), new BasicTextNormalizer()];

//Let's use a basic tokenizer (word-based tokens), and an NGram tokenizer, which creates trigrams (N=3).
//This should give us a good mix of keywords and partial keywords to look for when classifying text.
$tokenizers = [new BasicTokenizer(), new NGramTokenizer(3)];

$textClassifier = new TextClassifier(
    new TextProcessingPipeline($trainingProcessors, $classifyProcessors),
    $tokenizers, //Valid parameter types are an array of Tokenizers, a single Tokenizer, or a TextTokenizingPipeline.
    new NaiveBayes() //use Naive-Bayes as the classifier
);

$textClassifier->trainFromFile(__DIR__ . '/sentiment-train.txt');

$phrases = [
    'this is fantastic',
    'everything is great',
    'you are wonderful',
    'this is terrible',
    'everything is unpleasant',
    'you are awful',
];

echo $textClassifier->classifier->toJson() . PHP_EOL;

foreach ($phrases as $phrase) {
    echo $phrase . ' - ' . $textClassifier->classify($phrase) . PHP_EOL;
}
