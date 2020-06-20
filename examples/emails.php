<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Permafrost\TextClassifier\TextClassifier;
use Permafrost\TextClassifier\Classifiers\NaiveBayes;
use Permafrost\TextClassifier\Pipelines\TextProcessingPipeline;
use Permafrost\TextClassifier\Tokenizers\EmailAddressTokenizer;
use Permafrost\TextClassifier\Processors\EmailAddressNormalizer;

$processors = new TextProcessingPipeline([
    new EmailAddressNormalizer(),
]);

$tc = new TextClassifier($processors, [new EmailAddressTokenizer()], new NaiveBayes());
$tc = $tc->trainFromFile(__DIR__ . '/email-train.txt');

$emails = [
    'blah44657457@whatever.rut',
    'john@gmail.com',
];

foreach ($emails as $email) {
    echo "classification for '$email': " . $tc->classify($email) . PHP_EOL;
}