<?php

namespace Permafrost\TextClassifier\Pipelines;

use Permafrost\TextClassifier\Support\ArrayUtils;

class TextTokenizingPipeline extends BasicPipeline
{
    /**
     * Run the pipeline, passing the processed text successively to each tokenizer with it, then return the result.
     *
     * @param string $text
     * @param string $mode
     * @return array
     */
    public function run(string $text, string $mode = self::MODE_UNSPECIFIED)
    {
        $result = [];

        /* @var \Permafrost\TextClassifier\Tokenizers\Tokenizer $tokenizer */

        //if (empty($mode) || $mode === self::MODE_UNSPECIFIED || $mode === self::MODE_TRAIN) {
        foreach ($this->trainingItems as $tokenizer) {
            $result[] = $tokenizer->tokenize($text);
        }
//        } elseif ($mode === self::MODE_CLASSIFY) {
//            foreach ($this->classifyItems as $tokenizer) {
//                $result[] = $tokenizer->tokenize($text);
//            }
//        }

        return ArrayUtils::flatten($result);
    }
}
