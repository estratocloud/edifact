<?php

namespace Metroplex\Edifact;

/**
 * Represent a segment of an EDI message.
 */
class Segment
{
    /**
     * @var string $name The name of the segment.
     */
    protected $name;

    /**
     * @var array $elements The data elements for this segment.
     */
    protected $elements;


    /**
     * Create a new instance.
     *
     * @param string $name The name of the segment.
     * @param array $elements The data elements for this segment.
     */
    public function __construct($name, ...$elements)
    {
        $this->name = $name;
        $this->elements = $elements;
    }


    /**
     * Get the name of this segment.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get all the elements from the segment.
     *
     * @return array
     */
    public function getallElements()
    {
        return $this->elements;
    }


    /**
     * Get an element from the segment.
     *
     * @param int $key The element to get
     *
     * @return mixed
     */
    public function getElement($key)
    {
        if (!isset($this->elements[$key])) {
            return;
        }

        return $this->elements[$key];
    }
}
