<?php

namespace Permafrost\TextClassifier\Tokenizers;

interface Tokenizer
{
    public function tokenize(string $text): array;
}
