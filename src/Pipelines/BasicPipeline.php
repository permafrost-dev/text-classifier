<?php

namespace Permafrost\TextClassifier\Pipelines;

abstract class BasicPipeline implements Pipeline
{
    public const MODE_UNSPECIFIED = 'unspecified';
    public const MODE_TRAIN = 'train';
    public const MODE_CLASSIFY = 'classify';

    protected $trainingItems = [];
    protected $classifyItems = [];

    /**
     * Create a pipeline, specifying items for training and classification; if the classification items are not
     * specified, the training items will also be run during classification mode.  Provide an empty array to
     * avoid this behavior.
     *
     * @param array $trainingItems
     * @param array|null $classifyItems
     */
    public function __construct(array $trainingItems = [], ?array $classifyItems = null)
    {
        $this->trainingItems = $trainingItems;
        $this->classifyItems = $classifyItems ?? $trainingItems;
    }

    /**
     * Adds an item to the pipeline.
     *
     * @param $item
     *
     * @return \Permafrost\TextClassifier\Pipelines\Pipeline
     */
    public function add($item): Pipeline
    {
        $this->items[] = $item;

        return $this;
    }

    abstract public function run(string $text, string $mode = self::MODE_UNSPECIFIED);
}
