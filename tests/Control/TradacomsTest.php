<?php

namespace Metroplex\EdifactTests\Control;

use function array_slice;
use function file_get_contents;
use function iterator_to_array;
use Metroplex\Edifact\Control\Characters;
use Metroplex\Edifact\Control\Tradacoms;
use Metroplex\Edifact\Parser;
use Metroplex\Edifact\Tokenizer;
use PHPUnit\Framework\TestCase;

class TradacomsTest extends TestCase
{

    public function test1()
    {
        $message = file_get_contents(__DIR__ . "/../data/tradacoms.edi");
        $tradacoms = new Tradacoms;

        $parser = new Parser;
        $segments = $parser->parse($message, $tradacoms);
        $segments = iterator_to_array($segments);
        print_r($segments);
        return;
    }
}
