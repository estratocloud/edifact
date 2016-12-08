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
                    $element = array_map(
                        function($string) {
                            return $this->escape($string);
                        },
                        $element
                    );
                    $message .= implode($this->componentSeparator, $element);
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
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        $control_characters = [
            $this->escapeCharacter => $this->escapeCharacter . $this->escapeCharacter,
            $this->componentSeparator => $this->escapeCharacter . $this->componentSeparator,
            $this->dataSeparator => $this->escapeCharacter . $this->dataSeparator,
            $this->segmentTerminator => $this->escapeCharacter . $this->segmentTerminator
        ];

        foreach($control_characters as $search => $replace) {
            $string = str_replace($search, $replace, $string);
        }

        return $string;
    }
}
