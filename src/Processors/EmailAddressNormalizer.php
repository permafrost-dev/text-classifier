<?php

namespace Permafrost\TextClassifier\Processors;

class EmailAddressNormalizer
{
    /**
     * Remove punctuation characters.
     */
    protected function removePunctuation(string $text): string
    {
        return preg_replace('~(\w)[,_\-\+\?!\'"]+~', '$1', $text);
    }

    /**
     * Convert the text to lowercase, and remove some unnecessary chars.
     * @param string $text
     * @return string
     */
    public function process(string $text): string
    {
        return $this->removePunctuation(
            strtolower($text)
        );
    }
}
