<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Token;

class TokenTest extends \PHPUnit_Framework_TestCase
{

    public function testType()
    {
        $token = new Token(Token::CONTENT, "ok");
        $this->assertSame(Token::CONTENT, $token->type);
    }


    public function testValue()
    {
        $token = new Token(Token::CONTENT, "ok");
        $this->assertSame("ok", $token->value);
    }
}
