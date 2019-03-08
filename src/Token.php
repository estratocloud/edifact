<?php

namespace Metroplex\Edifact;

final class Token
{
    const CONTENT               =   11;
    const COMPONENT_SEPARATOR   =   12;
    const DATA_SEPARATOR        =   13;
    const TERMINATOR            =   14;

    /** @var int */
    public $type;

    /** @var string */
    public $value;


    /**
     * @param int $type
     * @param string $value
     */
    public function __construct(int $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
