<?php

namespace Estrato\EdifactTests;

use Estrato\Edifact\Control\Tradacoms;
use Estrato\Edifact\Message;
use Estrato\Edifact\Parser;
use Estrato\Edifact\Segments\Segment;
use Estrato\Edifact\Serializer;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function str_replace;

final class TradacomsTest extends TestCase
{
    public function testTradacoms1(): void
    {
        $edifact = new Serializer(new Tradacoms());

        $result = $edifact->serialize(
            new Segment('STX', 'ANA', '1', ['12345', 'Huws Gray Ltd'], ['6789', 'John Paul'], ['220101', '120000'], '100', '', 'INVFIL'),
            new Segment('MHD', '1', ['INVFIL', '9']),
            new Segment('TYP', '0700', 'INVOICES'),
        );

        self::assertSame("STX=ANA+1+12345:Huws Gray Ltd+6789:John Paul+220101:120000+100++INVFIL'MHD=1+INVFIL:9'TYP=0700+INVOICES'", $result);
    }
}
