# text-classifier
Performs basic text classification using algorithms such as Naive-Bayes.

### Example - Sentiment Analysis

See `examples/sentiment.php` for a working demo.

```php
<?php

use Permafrost\TextClassifier\TextClassifier;
use Permafrost\TextClassifier\Classifiers\NaiveBayes;
use Permafrost\TextClassifier\Tokenizers\BasicTokenizer;
use Permafrost\TextClassifier\Processors\BasicTextNormalizer;

//first, create the tokenizer, text preprocessor, and classifier
// - a number of training words (text) and categories are provided to the classifier
// - the preprocessor normalizes the text before tokenization
// - once it's been processed, the text is converted into tokens (words, bigrams, trigrams, ngrams, etc.)
//
$tokenizer = new BasicTokenizer();
$processor = new BasicTextNormalizer();
$classifier = new NaiveBayes();

//next, create an instance of TextClassifier with the tokenizer, processor, and classifier we've selected
$textClassifier = new TextClassifier($processor, $tokenizer, $classifier);

//now we teach the classifier how to classify text using a training file named sentiment-train.txt,
//which contains the following:
//positive|good
//positive|fantastic
//...
//negative|terrible
//...
$textClassifier->trainFromFile(__DIR__ . '/sentiment-train.txt');

//now that we've trained the classifier, we'll give it some sample phrases to classify:
$phrases = [
    'this is fantastic',
    'everything is great',
    'this is terrible!',
    'everything is unpleasant',
];

foreach($phrases as $phrase) {
    echo $phrase . ' - ' . $textClassifier->classify($phrase) . PHP_EOL;
}
```

Resulting output:
```
this is fantastic - positive
everything is great - positive
this is terrible! - negative
everything is unpleasant - negative
```