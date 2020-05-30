<?php

use PHPUnit\Framework\TestCase;
use Permafrost\TextClassifier\Utilities\NGram;

class NGramTest extends TestCase
{
    public function testClassCanBeConstructed()
    {
        new NGram(1, 'foo');
        $this->assertEquals(true, true);
    }

    public function testNMustBeGreaterThanOne()
    {
        $this->expectException(\Permafrost\TextClassifier\Exceptions\InvalidArgumentException::class);
        new NGram(-1, 'foo');
    }

    public function testParameterIsCastToString()
    {
        $class = new class() {
            public function __toString()
            {
                return 'foo';
            }
        };

        $nGram = new NGram(1, $class);

        $this->assertEquals($nGram->getString(), (string) $class);
    }

    public function testItGeneratesCorrect1NGramForString()
    {
        $nGram = new NGram(1, 'test');

        $this->assertEquals(['t', 'e', 's', 't'], $nGram->get());
    }

    public function testItGeneratesCorrect2NGramForString()
    {
        $nGram = new NGram(2, 'test');

        $this->assertEquals(['te', 'es', 'st'], $nGram->get());
    }

    public function testItReturnsEmptyArrayWhenNIsLargerThanStringLength()
    {
        $nGram = new NGram(10, 'test');

        $this->assertEquals([], $nGram->get());
    }

    public function testItReturnsEmptyArrayWhenStringIsEmpty()
    {
        $nGram = new NGram(1, '');
        $this->assertEquals([], $nGram->get());
    }

    public function testItGeneratesCorrectNGramForLargeString()
    {
        $nGram = new NGram(10, 'This is long.');
        $this->assertEquals([
                        'This is lo',
                        'his is lon',
                        'is is long',
                        's is long.',
                ], $nGram->get());
    }

    public function testStaticBigramWrapperReturnsRightNGram()
    {
        $this->assertEquals(['te', 'es', 'st'], NGram::bigram('test'));
    }

    public function testStaticTrigramWrapperReturnsRightNGram()
    {
        $this->assertEquals(['tes', 'est'], NGram::trigram('test'));
    }

}