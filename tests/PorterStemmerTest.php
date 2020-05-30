<?php

use PHPUnit\Framework\TestCase;
use Permafrost\TextClassifier\Utilities\PorterStemmer;

class PorterStemmerTest extends TestCase
{
    public function testDoesNotStemRootWords(): void
    {
        $this->assertEquals('test', PorterStemmer::stem('test'));
        $this->assertEquals('word', PorterStemmer::stem('word'));
    }

    public function testStep1a(): void
    {
        $this->assertEquals('bless', PorterStemmer::stem('blesses'));
        $this->assertEquals('test', PorterStemmer::stem('tests'));
    }

    public function testStep1b(): void
    {
        $this->assertEquals('test', PorterStemmer::stem('tested'));
        $this->assertEquals('test', PorterStemmer::stem('testing'));
    }

    public function testStep1c(): void
    {
        $this->assertEquals('toi', PorterStemmer::stem('toy'));
        $this->assertEquals('toi', PorterStemmer::stem('toys'));
    }

    public function testStep2(): void
    {
        $this->assertEquals('abil', PorterStemmer::stem('ability'));
        $this->assertEquals('abil', PorterStemmer::stem('abilities'));
    }

    public function testStep3(): void
    {
        $this->assertEquals('well', PorterStemmer::stem('wellness'));
    }

    public function testStep4(): void
    {
        $this->assertEquals('commit', PorterStemmer::stem('commital'));
        $this->assertEquals('obviou', PorterStemmer::stem('obvious'));
    }

    public function testStep5(): void
    {
        $this->assertEquals('abl', PorterStemmer::stem('able'));
    }

}