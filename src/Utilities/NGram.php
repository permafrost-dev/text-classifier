<?php
/**
 * This class was taken from devinbeeuwkes/ngram.
 *
 * @see https://github.com/devinbeeuwkes/ngram
 *
 * @license MIT
 */

namespace Permafrost\TextClassifier\Utilities;

use Permafrost\TextClassifier\Exceptions\InvalidArgumentException;

class NGram
{
    /**
     * The length of the n-gram.
     *
     * @var int
     */
    protected $n;

    /**
     * @var string
     */
    protected $string;

    /**
     * NGram constructor.
     *
     * @param int $n
     * @param string $string
     *
     * @throws \Permafrost\TextClassifier\Exceptions\InvalidArgumentException
     */
    public function __construct(int $n, string $string)
    {
        $this->setN($n);
        $this->setString($string);
    }

    /**
     * Static wrapper for n-gram generator.
     *
     * @param string $text
     * @param int $n
     * @return array
     *
     * @throws \Permafrost\TextClassifier\Exceptions\InvalidArgumentException
     */
    public static function for(string $text, int $n = 3): array
    {
        return (new static($n, $text))->get();
    }

    /**
     * Static wrapper to generate a bigram.
     *
     * @param string $text
     * @return array
     *
     * @throws \Permafrost\TextClassifier\Exceptions\InvalidArgumentException
     */
    public static function bigram(string $text): array
    {
        return self::for($text, 2);
    }

    /**
     * Static wrapper to generate a trigram.
     *
     * @param string $text
     * @return array
     *
     * @throws \Permafrost\TextClassifier\Exceptions\InvalidArgumentException
     */
    public static function trigram(string $text): array
    {
        return self::for($text, 3);
    }

    /**
     * Generate the N-gram for the provided string.
     */
    public function get(): array
    {
        $nGrams = [];

        $text = $this->getString();
        $max = \mb_strlen($text);
        $n = $this->getN();
        for ($i = 0; $i + $n <= $max; ++$i) {
            $partial = '';
            for ($j = 0; $j < $n; ++$j) {
                $partial .= $text[$j + $i];
            }
            $nGrams[] = $partial;
        }

        return $nGrams;
    }

    public function getN(): int
    {
        return $this->n;
    }

    /**
     * Set the length of the n-gram.
     *
     * @param int $n
     * @return \Permafrost\TextClassifier\Utilities\NGram
     *
     * @throws \Permafrost\TextClassifier\Exceptions\InvalidArgumentException
     */
    public function setN(int $n): NGram
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Provided number cannot be smaller than 1');
        }

        $this->n = $n;

        return $this;
    }

    /**
     * Set the string to create the n-gram for.
     *
     * @param string $string
     *
     * @return \Permafrost\TextClassifier\Utilities\NGram
     */
    public function setString(string $string): NGram
    {
        $this->string = $string;

        return $this;
    }

    /**
     * Get the string used for the n-gram.
     */
    public function getString(): string
    {
        return $this->string;
    }
}
