<?php

namespace Permafrost\TextClassifier\Processors;

interface TextProcessor
{
    //public function __construct(bool $trainingProcessor = true, bool $classifyingProcessor = true, ...$parameters);

    public function process(string $text): string;
}
