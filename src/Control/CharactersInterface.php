<?php

namespace Estrato\Edifact\Control;

/**
 * Handle the control characters used in EDI messages.
 */
interface CharactersInterface
{
    /**
     * Set whether the UNA segment should be part of the message or not.
     */
    public function withUNASegment(bool $include): self;


    /**
     * Check whether the UNA segment should be part of the message or not.
     */
    public function includesUNASegment(): bool;


    /**
     * Set the control character used to separate components.
     */
    public function withComponentSeparator(string $character): self;


    /**
     * Get the control character used to separate components.
     */
    public function getComponentSeparator(): string;


    /**
     * Set the control character used to separate data elements.
     */
    public function withDataSeparator(string $character): self;


    /**
     * Get the control character used to separate data elements.
     */
    public function getDataSeparator(): string;


    /**
     * Get the character used to separate segment tags from their data components.
     */
    public function getSegmentSeparator(): string;


    /**
     * Set the control character used as a decimal point.
     */
    public function withDecimalPoint(string $character): self;


    /**
     * Get the control character used as a decimal point.
     */
    public function getDecimalPoint(): string;


    /**
     * Set the control character used as an escape character.
     */
    public function withEscapeCharacter(string $character): self;


    /**
     * Get the control character used as an escape character.
     */
    public function getEscapeCharacter(): string;


    /**
     * Set the control character used as a segment terminator.
     */
    public function withSegmentTerminator(string $character): self;


    /**
     * Get the control character used as a segment terminator.
     */
    public function getSegmentTerminator(): string;


    /**
     * Set the control character used as the reserved space.
     */
    public function withReservedSpace(string $character): self;


    /**
     * Get the control character used as the reserved space.
     */
    public function getReservedSpace(): string;
}
