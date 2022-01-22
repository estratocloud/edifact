<?php

namespace Estrato\Edifact;

final class Token
{
    public const CONTENT               =   11;
    public const COMPONENT_SEPARATOR   =   12;
    public const DATA_SEPARATOR        =   13;
    public const TERMINATOR            =   14;

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
