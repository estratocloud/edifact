<?php

namespace Estrato\EdifactTests\Control;

use Estrato\Edifact\Control\Characters;
use Estrato\Edifact\Exceptions\InvalidArgumentException;
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

    public function testInvalidControlCharacter1(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Control characters must only be a single character");
        $this->characters->withComponentSeparator("[]");
    }

    public function testInvalidControlCharacter2(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Control characters must only be a single character");
        $this->characters->withDataSeparator("");
    }
}
