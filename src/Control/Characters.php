<?php

namespace Metroplex\Edifact\Control;

use Metroplex\Edifact\Exceptions\InvalidArgumentException;

/**
 * Handle the control characters used in EDI messages.
 */
final class Characters implements CharactersInterface
{
    /**
     * @var string $componentSeparator The control character used to separate components.
     */
    private $componentSeparator = ":";

    /**
     * @var string $dataSeparator The control character used to separate data elements.
     */
    private $dataSeparator = "+";

    /**
     * @var string $decimalPoint The control character used as a decimal point.
     */
    private $decimalPoint = ",";

    /**
     * @var string $escapeCharacter The control character used as an escape character.
     */
    private $escapeCharacter = "?";

    /**
     * @var string $segmentTerminator The control character used as an segment terminator.
     */
    private $segmentTerminator = "'";

    /**
     * @var string $reservedSpace The reserved space.
     */
    private $reservedSpace = " ";


    /**
     * Set a control character.
     *
     * @param string $type The type of control character to set
     * @param string $character The character to set it to
     *
     * @return CharactersInterface
     */
    private function withControlCharacter($type, $character)
    {
        if (mb_strlen($character) !== 1) {
            throw new InvalidArgumentException("Control characters must only be a single character");
        }

        $characters = clone $this;
        $characters->$type = $character;

        return $characters;
    }


    /**
     * Set the control character used to separate components.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withComponentSeparator($character)
    {
        return $this->withControlCharacter("componentSeparator", $character);
    }


    /**
     * Get the control character used to separate components.
     *
     * @return string
     */
    public function getComponentSeparator()
    {
        return $this->componentSeparator;
    }


    /**
     * Set the control character used to separate data elements.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withDataSeparator($character)
    {
        return $this->withControlCharacter("dataSeparator", $character);
    }


    /**
     * Get the control character used to separate data elements.
     *
     * @return string
     */
    public function getDataSeparator()
    {
        return $this->dataSeparator;
    }


    /**
     * Set the control character used as a decimal point.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withDecimalPoint($character)
    {
        return $this->withControlCharacter("decimalPoint", $character);
    }


    /**
     * Get the control character used as a decimal point.
     *
     * @return string
     */
    public function getDecimalPoint()
    {
        return $this->decimalPoint;
    }


    /**
     * Set the control character used as an escape character.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withEscapeCharacter($character)
    {
        return $this->withControlCharacter("escapeCharacter", $character);
    }


    /**
     * Get the control character used as an escape character.
     *
     * @return string
     */
    public function getEscapeCharacter()
    {
        return $this->escapeCharacter;
    }


    /**
     * Set the control character used as an segment terminator.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withSegmentTerminator($character)
    {
        return $this->withControlCharacter("segmentTerminator", $character);
    }


    /**
     * Get the control character used as an segment terminator.
     *
     * @return string
     */
    public function getSegmentTerminator()
    {
        return $this->segmentTerminator;
    }


    /**
     * Set the control character used as the reserved space.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withReservedSpace($character)
    {
        return $this->withControlCharacter("reservedSpace", $character);
    }


    /**
     * Get the control character used as an the reserved space.
     *
     * @return string
     */
    public function getReservedSpace()
    {
        return $this->reservedSpace;
    }
}
