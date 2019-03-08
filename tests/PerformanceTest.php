<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Message;
use PHPUnit\Framework\TestCase;
use function extension_loaded;
use function file_exists;
use function file_get_contents;
use function file_put_contents;

class PerformanceTest extends TestCase
{
    private $tmp = __DIR__ . "/data/tmp.edi";

    public function setUp(): void
    {
        if (extension_loaded("xdebug")) {
            $this->markTestSkipped("Cannot test performance as xdebug makes things slow");
        }

        $data = file_get_contents(__DIR__ . "/data/wikipedia.edi");

        file_put_contents($this->tmp, "");
        for ($i = 0; $i < 9999; ++$i) {
            file_put_contents($this->tmp, $data, \FILE_APPEND);
        }
    }


    public function tearDown(): void
    {
        if (file_exists($this->tmp)) {
            unlink($this->tmp);
        }
    }


    public function testTokenizerPerformance()
    {
        $start = time();

        Message::fromFile($this->tmp);

        $finish = time();

        $this->assertLessThan(10, $finish - $start);
    }
}
