<?php

namespace Estrato\Edifact\Control;

use Estrato\Edifact\Exceptions\InvalidArgumentException;

use function strlen;

/**
 * Handle the control characters used in EDI messages.
 */
final class Characters implements CharactersInterface
{
    /**
     * @var bool $includeUNASegment Whether we should include the UNA segment or not.
     */
    private bool $includeUNASegment = true;

    /**
     * @var string $componentSeparator The control character used to separate components.
     */
    private string $componentSeparator = ":";

    /**
     * @var string $dataSeparator The control character used to separate data elements.
     */
    private string $dataSeparator = "+";

    /**
     * @var string $decimalPoint The control character used as a decimal point.
     */
    private string $decimalPoint = ",";

    /**
     * @var string $escapeCharacter The control character used as an escape character.
     */
    private string $escapeCharacter = "?";

    /**
     * @var string $segmentTerminator The control character used as an segment terminator.
     */
    private string $segmentTerminator = "'";

    /**
     * @var string $reservedSpace The reserved space.
     */
    private string $reservedSpace = " ";


    /**
     * Set a control character.
     */
    private function withControlCharacter(string $type, string $character): CharactersInterface
    {
        if (strlen($character) !== 1) {
            throw new InvalidArgumentException("Control characters must only be a single character");
        }

        $characters = clone $this;
        $characters->$type = $character;

        return $characters;
    }


    public function withUNASegment(bool $include): self
    {
        $characters = clone $this;
        $characters->includeUNASegment = $include;
        return $characters;
    }


    public function includesUNASegment(): bool
    {
        return $this->includeUNASegment;
    }


    public function withComponentSeparator(string $character): CharactersInterface
    {
        return $this->withControlCharacter("componentSeparator", $character);
    }


    public function getComponentSeparator(): string
    {
        return $this->componentSeparator;
    }


    public function withDataSeparator(string $character): CharactersInterface
    {
        return $this->withControlCharacter("dataSeparator", $character);
    }


    public function getDataSeparator(): string
    {
        return $this->dataSeparator;
    }


    public function getSegmentSeparator(): string
    {
        return $this->getDataSeparator();
    }


    public function withDecimalPoint(string $character): CharactersInterface
    {
        return $this->withControlCharacter("decimalPoint", $character);
    }


    public function getDecimalPoint(): string
    {
        return $this->decimalPoint;
    }


    public function withEscapeCharacter(string $character): CharactersInterface
    {
        return $this->withControlCharacter("escapeCharacter", $character);
    }


    public function getEscapeCharacter(): string
    {
        return $this->escapeCharacter;
    }


    public function withSegmentTerminator(string $character): CharactersInterface
    {
        return $this->withControlCharacter("segmentTerminator", $character);
    }


    public function getSegmentTerminator(): string
    {
        return $this->segmentTerminator;
    }


    public function withReservedSpace(string $character): CharactersInterface
    {
        return $this->withControlCharacter("reservedSpace", $character);
    }


    public function getReservedSpace(): string
    {
        return $this->reservedSpace;
    }
}
