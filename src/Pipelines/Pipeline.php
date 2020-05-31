<?php

namespace Permafrost\TextClassifier\Pipelines;

interface Pipeline
{
    public function add($item): self;

    public function run(string $text);
}