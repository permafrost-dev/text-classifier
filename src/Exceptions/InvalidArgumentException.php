<?php
/**
 * This class was taken from devinbeeuwkes/ngram.
 *
 * @see https://github.com/devinbeeuwkes/ngram
 *
 * @license MIT
 */

namespace Permafrost\TextClassifier\Exceptions;

class InvalidArgumentException extends TextClassifierException
{
    public function __construct($message = '')
    {
        parent::__construct($message);
    }
}