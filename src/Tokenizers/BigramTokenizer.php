<?php

namespace Permafrost\TextClassifier\Tokenizers;

use Permafrost\TextClassifier\Utilities\NGram;

class BigramTokenizer implements Tokenizer
{
    public function tokenize(string $text): array
    {
        return NGram::for($text, 2);
    }
}
