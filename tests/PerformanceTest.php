<?php

namespace Metroplex\EdifactTests;

use Metroplex\Edifact\Message;
use function extension_loaded;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function version_compare;

class PerformanceTest extends \PHPUnit_Framework_TestCase
{
    private $tmp = __DIR__ . "/data/tmp.edi";

    public function setUp()
    {
        if (extension_loaded("xdebug")) {
            $this->markTestSkipped("Cannot test performance as xdebug makes things slow");
        }

        if (version_compare(\PHP_VERSION, "7.0.0") < 0) {
            $this->markTestSkipped("Not optimised for versions earlier than PHP 7");
        }

        $data = file_get_contents(__DIR__ ."/data/wikipedia.edi");

        file_put_contents($this->tmp, "");
        for ($i = 0; $i < 9999; ++$i) {
            file_put_contents($this->tmp, $data, \FILE_APPEND);
        }
    }


    public function tearDown()
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
