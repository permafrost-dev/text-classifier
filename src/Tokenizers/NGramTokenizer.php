<?php

namespace Permafrost\TextClassifier\Tokenizers;

use Permafrost\TextClassifier\Utilities\NGram;

class NGramTokenizer implements Tokenizer
{
    public $size = 3;

    public function __construct(int $size = 3)
    {
        $this->size = $size;
    }

    public function tokenize(string $text): array
    {
        return array_merge(NGram::for($text, $this->size), explode(' ', $text));
    }
}
