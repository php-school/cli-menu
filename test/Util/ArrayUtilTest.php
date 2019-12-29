<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;
use function PhpSchool\CliMenu\Util\each;
use function PhpSchool\CliMenu\Util\mapWithKeys;

class ArrayUtilTest extends TestCase
{
    public function testMapWithStringKeys() : void
    {
        self::assertEquals(
            ['one' => 1, 'two' => 4, 'three' => 9],
            mapWithKeys(
                ['one' => 1, 'two' => 2, 'three' => 3],
                function (string $key, int $num) {
                    return $num * $num;
                }
            )
        );
    }

    public function testMapWithIntKeys() : void
    {
        self::assertEquals(
            [1, 4, 9],
            mapWithKeys(
                [1, 2, 3],
                function (string $key, int $num) {
                    return $num * $num;
                }
            )
        );
    }

    public function testEach() : void
    {
        $i = 0;
        $cb = function (int $k, int $v) use (&$i) {
            $i++;
        };

        each([1, 2, 3], $cb);
        self::assertEquals(3, $i);
    }
}
