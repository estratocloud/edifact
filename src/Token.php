<?php

namespace Estrato\Edifact;

final class Token
{
    public const CONTENT               =   11;
    public const COMPONENT_SEPARATOR   =   12;
    public const DATA_SEPARATOR        =   13;
    public const TERMINATOR            =   14;

    public int $type;

    public string $value;


    public function __construct(int $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
