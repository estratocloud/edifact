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
                    $message .= implode($this->componentSeparator, array_map([$this, 'escape'], $element));
                } else {
                    $message .= $this->escape($element);
                }
            }

            $message .= $this->segmentTerminator;
        }

        return $message;
    }


    /**
     * Escapes control characters.
     *
     * @param string $string The string to be escaped
     *
     * @return string
     */
    public function escape($string)
    {
        $characters = [
            $this->escapeCharacter,
            $this->componentSeparator,
            $this->dataSeparator,
            $this->segmentTerminator,
        ];

        $search = [];
        $replace = [];
        foreach ($characters as $character) {
            $search[] = $character;
            $replace[] = $this->escapeCharacter . $character;
        }

        return str_replace($search, $replace, $string);
    }
}
