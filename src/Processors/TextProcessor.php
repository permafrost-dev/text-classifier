<?php

namespace Permafrost\TextClassifier\Processors;

interface TextProcessor
{
    public function process(string $text): string;
}
