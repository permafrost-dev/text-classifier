# text-classifier
Performs basic text classification using algorithms such as Naive-Bayes.

---
##### Installation:   
You may install text-classifier using composer: 

> `composer require permafrost-dev/text-classifier`


Note: The higher-quality and more complete training data used to train the model, the more accurate the classifications will be.

***
#### Example - Email Address Classification

A common use-case for classifying text is to determine whether or not an email is spam or not spam.  While that's beyond
the scope of this example, we can try to determine if a given email address is spam or not spam based on its features.
*Note: all email addresses used for training/examples were randomly generated.  If your email address somehow ended up
within the sample data, please contact packages@permafrost.dev and it will be promptly removed.*


```php
<?php

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
```

Resulting output:

- `classification for 'blah44657457@whatever.rut': spam`
- `classification for 'john@gmail.com': valid`

This method can easily be applied to other areas for spam checking, such as classifiying user-provided domain names.


***

#### Example - Sentiment Analysis

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

//Use different processors for training and classifying.  Since we're using keyword tokens,
//add all lemmas for each token during training to increase the size of the training data.
$trainingProcessors = [new TextLemmatizer(new Lemmatizer()), new BasicTextNormalizer()];

//When classifying, let's remove stopwords in addition to basic text normalization, because
//we'll be processing phrases.
$classifyProcessors = [new StopwordRemover(), new BasicTextNormalizer()];

//Let's use a basic tokenizer (word-based tokens), and an NGram tokenizer, which creates 
//trigrams (N=3). This should give us a good mix of keywords and partial keywords to look
//for when classifying text.
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

- `this is fantastic - positive`
- `this is terrible! - negative`


***

With more robust pre-processing and tokenizing, these methods can be applied to other data, such as determining whether or not
an email message is likely a spam message, whether a given article is of interest to a user based on basic preferences, and so on.

This does only go so far, however - machine learning is recommended when highly-accurate results are needed.

