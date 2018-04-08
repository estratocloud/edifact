<?php

namespace Metroplex\Edifact\Control;

use Metroplex\Edifact\Segments\SegmentInterface;
use Metroplex\Edifact\Token;

/**
 * Handle the control characters used in TRADACOMS messages.
 */
final class Tradacoms implements CharactersInterface
{
    /**
     * @var string $segmentSeparator The control character used to separate segment tags from their components.
     */
    private $segmentSeparator = "=";

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
     * @inheritDoc
     */
    public function withComponentSeparator($character)
    {
        return $this->withControlCharacter("componentSeparator", $character);
    }


    /**
     * @inheritDoc
     */
    public function getComponentSeparator()
    {
        return $this->componentSeparator;
    }


    /**
     * @inheritDoc
     */
    public function withDataSeparator($character)
    {
        return $this->withControlCharacter("dataSeparator", $character);
    }


    /**
     * @inheritDoc
     */
    public function getDataSeparator()
    {
        return $this->dataSeparator;
    }


    /**
     * @inheritDoc
     */
    public function withDecimalPoint($character)
    {
        return $this->withControlCharacter("decimalPoint", $character);
    }


    /**
     * @inheritDoc
     */
    public function getDecimalPoint()
    {
        return $this->decimalPoint;
    }


    /**
     * @inheritDoc
     */
    public function withEscapeCharacter($character)
    {
        return $this->withControlCharacter("escapeCharacter", $character);
    }


    /**
     * @inheritDoc
     */
    public function getEscapeCharacter()
    {
        return $this->escapeCharacter;
    }


    /**
     * @inheritDoc
     */
    public function withSegmentTerminator($character)
    {
        return $this->withControlCharacter("segmentTerminator", $character);
    }


    /**
     * @inheritDoc
     */
    public function getSegmentTerminator()
    {
        return $this->segmentTerminator;
    }


    /**
     * @inheritDoc
     */
    public function withReservedSpace($character)
    {
        return $this->withControlCharacter("reservedSpace", $character);
    }


    /**
     * @inheritDoc
     */
    public function getReservedSpace()
    {
        return $this->reservedSpace;
    }


    /**
     * Set the character used to separate segment tags from their data components.
     *
     * @param string $character The character to use
     *
     * @return SegmentInterface
     */
    public function withSegmentSeparator($character)
    {
        return $this->withControlCharacter("segmentSeparator", $character);
    }


    /**
     * Get the character used to separate segment tags from their data components.
     *
     * @return string
     */
    public function getSegmentSeparator()
    {
        return $this->segmentSeparator;
    }


    /**
     * @inheritDoc
     */
    public function getControlCharacters()
    {
        return [
            $this->getSegmentSeparator() => Token::DATA_SEPARATOR,
            $this->getComponentSeparator() => Token::COMPONENT_SEPARATOR,
            $this->getDataSeparator() => Token::DATA_SEPARATOR,
            $this->getSegmentTerminator() => Token::TERMINATOR,
        ];
    }
}
