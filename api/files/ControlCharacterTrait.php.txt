<?php

namespace Metroplex\Edifact;

/**
 * Handle the control characters used in EDI messages.
 */
trait ControlCharacterTrait
{
    /**
     * @var string $componentSeparator The control character used to separate components.
     */
    protected $componentSeparator = ":";

    /**
     * @var string $dataSeparator The control character used to separate data elements.
     */
    protected $dataSeparator = "+";

    /**
     * @var string $decimalPoint The control character used as a decimal point.
     */
    protected $decimalPoint = ",";

    /**
     * @var string $escapeCharacter The control character used as an escape character.
     */
    protected $escapeCharacter = "?";

    /**
     * @var string $segmentTerminator The control character used as an segment terminator.
     */
    protected $segmentTerminator = "'";



    /**
     * Set a control character.
     *
     * @param string $type The type of control character to set
     * @param string $char The character to set it to
     *
     * @return static
     */
    protected function setControlCharacter($type, $char)
    {
        if (mb_strlen($char) !== 1) {
            throw new \InvalidArgumentException("Control characters must only be a single character");
        }
        $this->$type = $char;

        return $this;
    }


    /**
     * Set the control character used to separate components.
     *
     * @param string $char The character to use
     *
     * @return static
     */
    public function setComponentSeparator($char)
    {
        return $this->setControlCharacter("componentSeparator", $char);
    }


    /**
     * Set the control character used to separate data elements.
     *
     * @param string $char The character to use
     *
     * @return static
     */
    public function setDataSeparator($char)
    {
        return $this->setControlCharacter("dataSeparator", $char);
    }


    /**
     * Set the control character used as a decimal point.
     *
     * @param string $char The character to use
     *
     * @return static
     */
    public function setDecimalPoint($char)
    {
        return $this->setControlCharacter("decimalPoint", $char);
    }


    /**
     * Set the control character used as an escape character.
     *
     * @param string $char The character to use
     *
     * @return static
     */
    public function setEscapeCharacter($char)
    {
        return $this->setControlCharacter("escapeCharacter", $char);
    }


    /**
     * Set the control character used as an segment terminator.
     *
     * @param string $char The character to use
     *
     * @return static
     */
    public function setSegmentTerminator($char)
    {
        return $this->setControlCharacter("segmentTerminator", $char);
    }
}
