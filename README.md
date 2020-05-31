# text-classifier
Performs basic text classification using algorithms such as Naive-Bayes.

### Example - Sentiment Analysis

See `examples/sentiment.php` for a working demo.

```php
<?php

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
//during training to increase the size of the training data.
$trainingProcessors = [new TextLemmatizer(new Lemmatizer()), new BasicTextNormalizer()];

//When classifying, let's remove stopwords in addition to basic text normalization, because we'll be processing phrases.
$classifyProcessors = [new StopwordRemover(), new BasicTextNormalizer()];

//Let's use a basic tokenizer (word-based tokens), and an NGram tokenizer, which creates trigrams (N=3).
//This should give us a good mix of keywords and partial keywords to look for when classifying text.
$tokenizers = [new BasicTokenizer(), new NGramTokenizer(3)];

$textClassifier = new TextClassifier(
    new TextProcessingPipeline($trainingProcessors, $classifyProcessors),
    $tokenizers,
    new NaiveBayes() //use Naive-Bayes as the classifier
);

$textClassifier->trainFromFile(__DIR__ . '/sentiment-train.txt');

$phrases = [
    'this is fantastic',
    'this is terrible',
];

foreach ($phrases as $phrase) {
    echo $phrase . ' - ' . $textClassifier->classify($phrase) . PHP_EOL;
}
```

Resulting output:

`this is fantastic - positive`
`this is terrible! - negative`
