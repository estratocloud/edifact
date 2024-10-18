<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Control\Characters as ControlCharacters;
use Estrato\Edifact\Exceptions\ParseException;
use Estrato\Edifact\Token;
use Estrato\Edifact\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    /**
     * @var Tokenizer $tokenizer The instance we are testing.
     */
    private $tokenizer;

    public function setUp(): void
    {
        $this->tokenizer = new Tokenizer();
    }


    /**
     * @param string $message
     * @param array<Token> $expected
     */
    private function assertTokens(string $message, array $expected): void
    {
        $tokens = $this->tokenizer->getTokens("{$message}'", new ControlCharacters());

        $expected[] = new Token(Token::TERMINATOR, "'");

        $this->assertEquals($expected, $tokens);
    }


    public function testBasic(): void
    {
        $this->assertTokens("RFF+PD:50515", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "50515"),
        ]);
    }


    /**
     * Regression test for https://github.com/estratocloud/edifact/issues/14
     */
    public function testMultiple(): void
    {
        $this->assertTokens("RFF+PD:50515", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "50515"),
        ]);

        # Ensure we can use the same tokenizer instance for multiple messages
        $this->assertTokens("RFF+PD:50515", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "50515"),
        ]);
    }


    public function testEscape(): void
    {
        $this->assertTokens("RFF+PD?:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD:5"),
        ]);
    }
    public function testDoubleEscape(): void
    {
        $this->assertTokens("RFF+PD??:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD?"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "5"),
        ]);
    }
    public function testTripleEscape(): void
    {
        $this->assertTokens("RFF+PD???:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD?:5"),
        ]);
    }
    public function testQuadrupleEscape(): void
    {
        $this->assertTokens("RFF+PD????:5", [
            new Token(Token::CONTENT, "RFF"),
            new Token(Token::DATA_SEPARATOR, "+"),
            new Token(Token::CONTENT, "PD??"),
            new Token(Token::COMPONENT_SEPARATOR, ":"),
            new Token(Token::CONTENT, "5"),
        ]);
    }


    public function testIgnoreWhitespace(): void
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


    public function testNoTerminator(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage("Unexpected end of EDI message");
        $this->tokenizer->getTokens("TEST", new ControlCharacters());
    }
}
