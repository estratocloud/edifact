<?php

namespace Metroplex\Edifact;

/**
 * Serialize a bunch of segments into an EDI message string.
 */
class Serializer
{
    use ControlCharacterTrait;

    /**
     * Serialize all the passed segments.
     *
     * @return string
     */
    public function serialize($segments)
    {
        $message = "UNA";
        $message .= $this->componentSeparator;
        $message .= $this->dataSeparator;
        $message .= $this->decimalPoint;
        $message .= $this->escapeCharacter;
        $message .= " ";
        $message .= $this->segmentTerminator;

        foreach ($segments as $segment) {
            $message .= $segment->getName();
            foreach ($segment->getAllElements() as $element) {
                $message .= $this->dataSeparator;

                if (is_array($element)) {
                    $message .= implode($this->componentSeparator, $element);
                } else {
                    $message .= $element;
                }
            }

            $message .= $this->segmentTerminator;
        }

        return $message;
    }
}
