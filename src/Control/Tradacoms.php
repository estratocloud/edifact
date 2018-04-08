<?php

namespace Estrato\Edifact\Control;

use Estrato\Edifact\Exceptions\BadMethodCallException;

/**
 * Handle the control characters used in TRADACOMS messages.
 */
final class Tradacoms implements CharactersInterface
{
    public function withUNASegment(bool $include): self
    {
        if ($include) {
            throw new BadMethodCallException("TRADACOMS messages do not support a UNA segment");
        }
        return $this;
    }


    public function includesUNASegment(): bool
    {
        return false;
    }


    public function withComponentSeparator(string $character): self
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    public function getComponentSeparator(): string
    {
        return ":";
    }


    public function withDataSeparator(string $character): self
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    public function getDataSeparator(): string
    {
        return "+";
    }


    public function getSegmentSeparator(): string
    {
        return "=";
    }


    public function withDecimalPoint(string $character): self
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    public function getDecimalPoint(): string
    {
        return " ";
    }


    public function withEscapeCharacter(string $character): self
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    public function getEscapeCharacter(): string
    {
        return "?";
    }


    public function withSegmentTerminator(string $character): self
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    public function getSegmentTerminator(): string
    {
        return "'";
    }


    public function withReservedSpace(string $character): self
    {
        throw new BadMethodCallException("TRADACOMS messages do not support custom separators");
    }


    public function getReservedSpace(): string
    {
        return " ";
    }
}
