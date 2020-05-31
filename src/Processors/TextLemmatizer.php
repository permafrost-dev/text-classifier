<?php

namespace Permafrost\TextClassifier\Processors;

use Permafrost\TextClassifier\Support\ArrayUtils;
use Skyeng\Lemmatizer;

class TextLemmatizer implements TextProcessor
{
    /** @var \Skyeng\Lemmatizer $lemmatizer */
    protected $lemmatizer;

    public function __construct($lemmatizer = null)
    {
        $this->lemmatizer = $lemmatizer ?? new Lemmatizer();
    }

    public function process(string $text): string
    {
        $words = explode(' ', $text);
        $result = [];

        foreach ($words as $word) {
            $lemmas = $this->lemmatizer->getOnlyLemmas($word);
            $result[] = $word;
            foreach ($lemmas as $lemma) {
                if ($lemma !== $word) {
                    $result[] = $lemma;
                }
            }
        }

        return implode(' ', ArrayUtils::flatten($result));
    }

}
