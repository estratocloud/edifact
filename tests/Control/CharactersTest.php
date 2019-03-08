<?php

namespace Metroplex\EdifactTests\Control;

use Metroplex\Edifact\Control\Characters;
use Metroplex\Edifact\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CharactersTest extends TestCase
{
    /**
     * @var Characters $characters The instance we are testing.
     */
    private $characters;

    public function setUp(): void
    {
        $this->characters = new Characters();
    }

    public function testInvalidControlCharacter1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Control characters must only be a single character");
        $this->characters->withComponentSeparator("[]");
    }

    public function testInvalidControlCharacter2()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Control characters must only be a single character");
        $this->characters->withDataSeparator("");
    }
}
