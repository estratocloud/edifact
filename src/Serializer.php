<?php

namespace Estrato\Edifact;

use Estrato\Edifact\Control\Characters as ControlCharacters;
use Estrato\Edifact\Control\CharactersInterface as ControlCharactersInterface;
use Estrato\Edifact\Segments\SegmentInterface;

use function array_map;
use function implode;
use function is_array;
use function str_replace;

/**
 * Serialize a bunch of segments into an EDI message string.
 */
final class Serializer implements SerializerInterface
{
    /**
     * @var ControlCharactersInterface $characters The control characters to use when serializing.
     */
    private $characters;


    /**
     * @param ControlCharactersInterface|null $characters
     */
    public function __construct(?ControlCharactersInterface $characters = null)
    {
        if ($characters === null) {
            $characters = new ControlCharacters();
        }
        $this->characters = $characters;
    }


    /**
     * Serialize all the passed segments.
     *
     * @param SegmentInterface ...$segments The segments to serialize
     *
     * @return string
     */
    public function serialize(SegmentInterface ...$segments): string
    {
        $message = "UNA";
        $message .= $this->characters->getComponentSeparator();
        $message .= $this->characters->getDataSeparator();
        $message .= $this->characters->getDecimalPoint();
        $message .= $this->characters->getEscapeCharacter();
        $message .= $this->characters->getReservedSpace();
        $message .= $this->characters->getSegmentTerminator();

        foreach ($segments as $segment) {
            $message .= $segment->getSegmentCode();
            foreach ($segment->getAllElements() as $element) {
                $message .= $this->characters->getDataSeparator();

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
    private function escape(string $string): string
    {
        $characters = [
            $this->characters->getEscapeCharacter(),
            $this->characters->getComponentSeparator(),
            $this->characters->getDataSeparator(),
            $this->characters->getSegmentTerminator(),
        ];

        $search = [];
        $replace = [];
        foreach ($characters as $character) {
            $search[] = $character;
            $replace[] = $this->characters->getEscapeCharacter() . $character;
        }

        return str_replace($search, $replace, $string);
    }
}
