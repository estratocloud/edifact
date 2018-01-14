<?php

namespace Metroplex\EdifactTests\Control;

use Metroplex\Edifact\Control\Characters;
use Metroplex\Edifact\Exceptions\InvalidArgumentException;

class CharacterTest extends \PHPUnit_Framework_TestCase
{
    private $characters;

    public function setUp()
    {
        $this->characters = new Characters;
    }

    public function testInvalidControlCharacter1()
    {
        $this->setExpectedException(InvalidArgumentException::class, "Control characters must only be a single character");
        $this->characters->withComponentSeparator("[]");
    }

    public function testInvalidControlCharacter2()
    {
        $this->setExpectedException(InvalidArgumentException::class, "Control characters must only be a single character");
        $this->characters->withDataSeparator("");
    }
}
