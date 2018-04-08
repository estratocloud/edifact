<?php

namespace Metroplex\Edifact;

use Metroplex\Edifact\Control\Characters as ControlCharacters;
use Metroplex\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Metroplex\Edifact\Segments\SegmentInterface;
use function array_map;
use function implode;
use function in_array;
use function is_array;
use function str_replace;

/**
 * Serialize a bunch of segments into an EDI message string.
 */
final class Serializer
{
    /**
     * @var ControlCharactersInterface $characters The control characters to use when serializing.
     */
    private $characters;

    public function __construct(ControlCharactersInterface $characters = null)
    {
        if ($characters === null) {
            $characters = new ControlCharacters;
        }
        $this->characters = $characters;
    }


    /**
     * Serialize all the passed segments.
     *
     * @param SegmentInterface[] $segments The segments to serialize
     *
     * @return string
     */
    public function serialize(SegmentInterface ...$segments)
    {
        $message = "UNA";
        $message .= $this->characters->getComponentSeparator();
        $message .= $this->characters->getDataSeparator();
        $message .= $this->characters->getDecimalPoint();
        $message .= $this->characters->getEscapeCharacter();
        $message .= " ";
        $message .= $this->characters->getSegmentTerminator();

        foreach ($segments as $segment) {
            $message .= $segment->getSegmentCode();

            $first = true;
            foreach ($segment->getAllElements() as $element) {
                if ($first) {
                    $first = false;
                    $message .= $this->characters->getSegmentSeparator();
                } else {
                    $message .= $this->characters->getDataSeparator();
                }

                if (is_array($element)) {
                    $message .= implode($this->characters->getComponentSeparator(), array_map([$this, 'escape'], $element));
                } else {
                    $message .= $this->escape($element);
                }
            }

            $message .= $this->characters->getSegmentTerminator();
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
    private function escape($string)
    {
        $characters = [
            $this->characters->getEscapeCharacter(),
            $this->characters->getComponentSeparator(),
            $this->characters->getDataSeparator(),
            $this->characters->getSegmentTerminator(),
            $this->characters->getSegmentSeparator(),
        ];

        $search = [];
        $replace = [];
        foreach ($characters as $character) {
            if (in_array($character, $search, true)) {
                continue;
            }
            $search[] = $character;
            $replace[] = $this->characters->getEscapeCharacter() . $character;
        }

        return str_replace($search, $replace, $string);
    }
}
