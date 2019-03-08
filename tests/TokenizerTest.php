<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Control\Characters as ControlCharacters;
use Metroplex\Edifact\Exceptions\ParseException;
use Metroplex\Edifact\Token;
use Metroplex\Edifact\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    /**
     * @var Tokenizer $tokenizer The instance we are testing.
     */
    private $tokenizer;

    public function setUp(): void
    {
        $this->tokenizer = new Tokenizer;
    }

    private function assertTokens($message, array $expected)
    {
        $tokens = $this->tokenizer->getTokens("{$message}'", new ControlCharacters);

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
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage("Unexpected end of EDI message");
        $this->tokenizer->getTokens("TEST", new ControlCharacters);
    }
}
