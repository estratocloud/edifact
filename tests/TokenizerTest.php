<?php

namespace Metroplex\Edifact\Tests;

use Metroplex\Edifact\Token;
use Metroplex\Edifact\Tokenizer;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    protected $tokenizer;

    public function setUp()
    {
        $this->tokenizer = new Tokenizer;
    }

    protected function assertTokens($message, array $expected)
    {
        $tokens = $this->tokenizer->getTokens("{$message}'");

        $expected[] = new Token(Token::TERMINATOR, "'");

        $this->assertEquals($expected, $tokens);
    }


    public function testBasic()
    {
        $this->assertTokens("RFF+PD:50515", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "50515"),
        ]);
    }


    public function testEscape()
    {
        $this->assertTokens("RFF+PD?:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD:5"),
        ]);
    }
    public function testDoubleEscape()
    {
        $this->assertTokens("RFF+PD??:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD?"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "5"),
        ]);
    }
    public function testTripleEscape()
    {
        $this->assertTokens("RFF+PD???:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD?:5"),
        ]);
    }
    public function testQuadrupleEscape()
    {
        $this->assertTokens("RFF+PD????:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD??"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "5"),
        ]);
    }


    public function testIgnoreWhitespace()
    {
        $this->assertTokens("RFF:5'\nDEF:6", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "5"),
            new Token(Token::TERMINATOR, "'"),
            new Token(Token::CONTENT, "DEF"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "6"),
        ]);
    }


    public function testNoTerminator()
    {
        $this->setExpectedException("RuntimeException", "Unexpected end of EDI message");
        $this->tokenizer->getTokens("TEST");
    }
}
