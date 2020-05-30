<?php

namespace Permafrost\TextClassifier\Tokenizers;

class BasicTokenizer implements Tokenizer
{
    public function tokenize(string $text): array
    {
        return preg_split('~[^\w]+~', $text, 0, PREG_SPLIT_NO_EMPTY);
    }
}
