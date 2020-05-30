<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Permafrost\TextClassifier\TextClassifier;
use Permafrost\TextClassifier\Classifiers\NaiveBayes;
use Permafrost\TextClassifier\Tokenizers\BasicTokenizer;
use Permafrost\TextClassifier\Processors\BasicTextNormalizer;

$tokenizer = new BasicTokenizer();
$processor = new BasicTextNormalizer();
$classifier = new NaiveBayes();
$textClassifier = new TextClassifier($processor, $tokenizer, $classifier);

$textClassifier->trainFromFile(__DIR__ . '/sentiment-train.txt');

$phrases = [
    'this is fantastic',
    'everything is great',
    'this is terrible!',
    'everything is unpleasant',
];

foreach($phrases as $phrase) {
    echo $phrase . ' - ' . $textClassifier->classify($phrase) . PHP_EOL;
}

