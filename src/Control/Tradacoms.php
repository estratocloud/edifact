<?php

namespace Metroplex\Edifact\Control;

use Metroplex\Edifact\Exceptions\BadMethodCallException;

/**
 * Handle the control characters used in TRADACOMS messages.
 */
final class Tradacoms implements CharactersInterface
{
    /**
     * Get the character used to separate segment tags from their data components.
     *
     * @return string
     */
    public function getSegmentSeparator()
    {
        return "=";
    }


    /**
     * @inheritDoc
     */
    public function getComponentSeparator()
    {
        return ":";
    }


    /**
     * @inheritDoc
     */
    public function getDataSeparator()
    {
        return "+";
    }


    /**
     * @inheritDoc
     */
    public function getDecimalPoint()
    {
        return " ";
    }


    /**
     * @inheritDoc
     */
    public function getEscapeCharacter()
    {
        return "?";
    }


    /**
     * @inheritDoc
     */
    public function getSegmentTerminator()
    {
        return "'";
    }


    /**
     * @inheritDoc
     */
    public function getReservedSpace()
    {
        return " ";
    }


    /**
     * @inheritDoc
     */
    public function withComponentSeparator($character)
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    /**
     * @inheritDoc
     */
    public function withDataSeparator($character)
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    /**
     * @inheritDoc
     */
    public function withDecimalPoint($character)
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    /**
     * @inheritDoc
     */
    public function withEscapeCharacter($character)
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    /**
     * @inheritDoc
     */
    public function withSegmentTerminator($character)
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    /**
     * @inheritDoc
     */
    public function withReservedSpace($character)
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }
}
