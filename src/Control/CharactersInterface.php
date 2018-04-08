<?php

namespace Metroplex\Edifact\Control;

/**
 * Handle the control characters used in EDI messages.
 */
interface CharactersInterface
{
    /**
     * Set the control character used to separate components.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withComponentSeparator($character);


    /**
     * Get the control character used to separate components.
     *
     * @return string
     */
    public function getComponentSeparator();


    /**
     * Set the control character used to separate data elements.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withDataSeparator($character);


    /**
     * Get the control character used to separate data elements.
     *
     * @return string
     */
    public function getDataSeparator();


    /**
     * Set the control character used as a decimal point.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withDecimalPoint($character);


    /**
     * Get the control character used as a decimal point.
     *
     * @return string
     */
    public function getDecimalPoint();


    /**
     * Set the control character used as an escape character.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withEscapeCharacter($character);


    /**
     * Get the control character used as an escape character.
     *
     * @return string
     */
    public function getEscapeCharacter();


    /**
     * Set the control character used as an segment terminator.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withSegmentTerminator($character);


    /**
     * Get the control character used as an segment terminator.
     *
     * @return string
     */
    public function getSegmentTerminator();


    /**
     * Set the control character used as the reserved space.
     *
     * @param string $character The character to use
     *
     * @return CharactersInterface
     */
    public function withReservedSpace($character);


    /**
     * Get the control character used as an the reserved space.
     *
     * @return string
     */
    public function getReservedSpace();


    /**
     * Get the characters specifically used for control only.
     *
     * @return array Keyed by the control character with the value being a Token:: type
     */
    public function getControlCharacters();
}
