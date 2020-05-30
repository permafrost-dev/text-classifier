<?php

namespace Permafrost\TextClassifier\Processors;

use Permafrost\TextClassifier\Utilities\PorterStemmer;

class BasicTextNormalizer implements TextProcessor
{
    /**
     * Remove a basic list of stopwords.
     */
    protected function removeStopwords(string $text): string
    {
        return preg_replace('~\b('.
            'one|two|three|four|five|six|seven|eight|nine|ten|a|about|above|after|again|against|all|am|an|and|any|are|aren\'t|as|at|'.
            'be|because|been|before|being|below|between|both|but|by|can\'t|cannot|could|couldn\'t|did|didn\'t|do|does|doesn\'t|doing|don\'t|'.
            'down|during|each|few|for|from|further|had|hadn\'t|has|hasn\'t|have|haven\'t|having|he|he\'d|he\'ll|he\'s|her|here|here\'s|hers|'.
            'herself|him|himself|his|how|how\'s|i|i\'d|i\'ll|i\'m|i\'ve|if|in|into|is|isn\'t|it|it\'s|its|itself|let\'s|me|more|most|mustn\'t|'.
            'my|myself|no|nor|not|of|off|on|once|only|or|other|ought|our|ours|ourselves|out|over|own|same|shan\'t|she|she\'d|she\'ll|she\'s|'.
            'should|shouldn\'t|so|some|such|than|that|that\'s|the|their|theirs|them|themselves|then|there|there\'s|these|they|they\'d|'.
            'they\'ll|they\'re|they\'ve|this|those|through|to|too|under|until|up|very|was|wasn\'t|we|we\'d|we\'ll|we\'re|we\'ve|were|'.
            'weren\'t|what|what\'s|when|when\'s|where|where\'s|which|while|who|who\'s|whom|why|why\'s|will|with|won\'t|would|wouldn\'t|'.
            'you|you\'d|you\'ll|you\'re|you\'ve|your|yours|yourself|yourselves)+\b~', ' ', $text);
    }

    /**
     * Remove punctuation characters.
     */
    protected function removePunctuation(string $text): string
    {
        return preg_replace('~(\w)[\.,\?!\'"]+~', '$1', $text);
    }

    /**
     * Compress repeated whitespace into a single character.
     */
    protected function compressSpaces(string $text): string
    {
        return preg_replace('~([\s\t]){2,}~', '$1', $text);
    }

    /**
     * Convert CR/LF characters into spaces.
     */
    protected function newlinesToSpaces(string $text): string
    {
        return str_replace(['\r', '\n'], ' ', $text);
    }

    /**
     * Compress strings like 'wooowwwwww!' to 'wooww!'.
     */
    protected function compressRepeats(string $text): string
    {
        return preg_replace('~(.)\1+~', '$1$1', $text);
    }

    protected function removeShortTokens(string $text): string
    {
        return preg_replace('~(\b)\w{1,2}(\b)~', '$1$2', $text);
    }

    public function process(string $text): string
    {
        $result = strtolower(trim($text));
        $result = $this->removeStopwords($result);
        $result = $this->removePunctuation($result);
        $result = PorterStemmer::stem($result);
        $result = $this->removeShortTokens($result);
        $result = $this->compressRepeats($result);
        $result = $this->newlinesToSpaces($result);
        $result = $this->compressSpaces($result);

        return $result;
    }
}
