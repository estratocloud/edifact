<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Token;
use Metroplex\Edifact\Tokenizer;

class ControlCharacterTest extends \PHPUnit_Framework_TestCase
{
    protected $tokenizer;

    public function setUp()
    {
        $this->tokenizer = new Tokenizer;
    }

    public function testInvalidControlCharacter1()
    {
        $this->setExpectedException("InvalidArgumentException", "Control characters must only be a single character");
        $this->tokenizer->setComponentSeparator("[]");
    }

    public function testInvalidControlCharacter2()
    {
        $this->setExpectedException("InvalidArgumentException", "Control characters must only be a single character");
        $this->tokenizer->setDataSeparator("");
    }
}
