<?php

namespace Permafrost\TextClassifier\Processors;

use Permafrost\TextClassifier\Utilities\PorterStemmer;

class WordStemmer implements TextProcessor
{
    public function process(string $text): string
    {
        $words = explode(' ', $text);
        $result = [];

        foreach ($words as $word) {
            $result[] = PorterStemmer::stem($word);
        }

        return implode(' ', $result);
    }
}
