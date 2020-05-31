<?php

namespace Permafrost\TextClassifier\Processors;

use Permafrost\TextClassifier\Utilities\PorterStemmer;

class LimitedWordStemmer implements TextProcessor
{
    public function process(string $text): string
    {
        $words = explode(' ', $text);
        $result = [];

        foreach ($words as $word) {
            $stemmed = PorterStemmer::stem($word);

            //only keep the stemmed word if it's not drastically different from the original word
            if (strlen($stemmed) > 2 && strlen($stemmed) > (strlen($word) / 3)) {
                $result[] = $stemmed;
            } else {
                $result[] = $word;
            }
        }

        return implode(' ', $result);
    }
}
