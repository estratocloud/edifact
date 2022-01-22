<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testType(): void
    {
        $token = new Token(Token::CONTENT, "ok");
        $this->assertSame(Token::CONTENT, $token->type);
    }


    public function testValue(): void
    {
        $token = new Token(Token::CONTENT, "ok");
        $this->assertSame("ok", $token->value);
    }
}
