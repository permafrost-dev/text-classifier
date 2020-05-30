<?php

namespace Permafrost\TextClassifier\Processors;

use Permafrost\TextClassifier\Utilities\PorterStemmer;

class HashtagNormalizer implements TextProcessor
{
    /**
     * Replace in-word underscores with spaces, remove leading and trailing underscores.
     */
    protected function removeUnderscores(string $text): string
    {
        return str_replace('_', ' ', trim($text, '_'));
    }

    protected function stem(string $text): string
    {
        return PorterStemmer::stem($text);
    }

    protected function camelcaseToWords(string $text): string
    {
        return preg_replace('~(?<=.)([A-Z])~u', ' $0 ', $text);
    }

    protected function stripHashSymbol(string $text): string
    {
        return ltrim($text, '#');
    }

    protected function normalize(string $text): string
    {
        return strtolower(trim($text));
    }

    protected function removeDigits(string $text): string
    {
        return preg_replace('~\d+~u', '', $text);
    }

    protected function compressSpaces(string $text): string
    {
        return preg_replace('~\s{2,}~', ' ', $text);
    }

    public function process(string $text): string
    {
        $result = $text;
        $result = $this->normalize($result);
        $result = $this->stripHashSymbol($result);
        $result = $this->removeUnderscores($result);
        $result = $this->removeDigits($result);
        $result = $this->compressSpaces($result);
        if (strpos($result, ' ') !== false) {
            $words = explode(' ', $result);
            foreach ($words as $word) {
                $word = $this->stem($word);
            }
            $result = implode(' ', $words);
        }
        $result = $this->stem($result);

        return $result;
    }
}
