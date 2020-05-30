<?php

namespace Permafrost\TextClassifier\Tokenizers;

use Permafrost\TextClassifier\Utilities\NGram;

class HashtagTokenizer implements Tokenizer
{
    public function tokenize(string $text): array
    {
        $words = array_filter(explode(' ', $text), static function ($value) {
            $length = strlen($value);

            return $length < 6
                && $length > 4;
        });

        return array_merge(NGram::for($text, 2), $words);
    }
}
