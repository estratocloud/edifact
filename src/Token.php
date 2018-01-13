<?php

namespace Metroplex\Edifact;

final class Token
{
    const CONTENT               =   11;
    const COMPONENT_SEPARATOR   =   12;
    const DATA_SEPARATOR        =   13;
    const TERMINATOR            =   14;

    public $type;
    public $value;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
