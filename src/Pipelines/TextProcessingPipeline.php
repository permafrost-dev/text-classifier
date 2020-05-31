<?php

namespace Permafrost\TextClassifier\Pipelines;

class TextProcessingPipeline extends BasicPipeline
{
    /**
     * Run the pipeline for the specified mode, passing the processed text successively to each processor within it.
     * Returns the processed value of $text.
     *
     * @param string $text
     * @param string $mode
     * @return string
     */
    public function run(string $text, string $mode = self::MODE_UNSPECIFIED)
    {
        $result = $text;

        /* @var \Permafrost\TextClassifier\Processors\TextProcessor $processor */

        if (empty($mode) || $mode === self::MODE_UNSPECIFIED || $mode === self::MODE_TRAIN) {
            foreach ($this->trainingItems as $processor) {
                $result = $processor->process($result);
            }
        } elseif ($mode === self::MODE_CLASSIFY) {
            foreach ($this->classifyItems as $processor) {
                //echo 'Running classify processor ' . get_class($processor) . PHP_EOL;
                $result = $processor->process($result);
            }
        }

        return $result;
    }
}
