<?php

namespace Metroplex\EdifactTests;

use duncan3dc\PhpIni\Ini;
use duncan3dc\PhpIni\Settings;
use Metroplex\Edifact\Message;
use PHPUnit\Framework\TestCase;

use function extension_loaded;
use function file_exists;
use function file_get_contents;
use function file_put_contents;

class PerformanceTest extends TestCase
{
    /** @var string */
    private $tmp = __DIR__ . "/data/tmp.edi";

    /** @var Ini */
    private $ini;


    public function setUp(): void
    {
        if (extension_loaded("xdebug")) {
            $this->markTestSkipped("Cannot test performance as xdebug makes things slow");
        }
        if (extension_loaded("pcov")) {
            $this->markTestSkipped("Cannot test performance as pcov makes use too much memory");
        }

        # Allow this test to use up to 512mb of memory
        $this->ini = new Ini();
        $this->ini->set(Settings::MEMORY_LIMIT, (string) (512 * 1024 * 1024));

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
        $this->ini->cleanup();
    }


    public function testTokenizerPerformance(): void
    {
        $start = time();

        Message::fromFile($this->tmp);

        $finish = time();

        $this->assertLessThan(10, $finish - $start);
    }
}
