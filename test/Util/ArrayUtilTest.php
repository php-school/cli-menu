<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

use function PhpSchool\CliMenu\Util\collect;
use function PhpSchool\CliMenu\Util\each;
use function PhpSchool\CliMenu\Util\filter;
use function PhpSchool\CliMenu\Util\mapWithKeys;
use function PhpSchool\CliMenu\Util\max;

class ArrayUtilTest extends TestCase
{
    public function testMapWithStringKeys(): void
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

    public function testMapWithIntKeys(): void
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

    public function testEach(): void
    {
        $i = 0;
        $cb = function (int $k, int $v) use (&$i) {
            $i++;
        };

        each([1, 2, 3], $cb);
        self::assertEquals(3, $i);
    }

    public function testMax(): void
    {
        self::assertEquals(0, max([]));
        self::assertEquals(3, max([1, 2, 3]));
        self::assertEquals(6, max([1, 6, 3]));
    }

    public function testFilter(): void
    {
        $cb = function (int $k, int $v) {
            return $v > 3;
        };

        self::assertEquals([3 => 4, 4 => 5, 5 => 6], filter([1, 2, 3, 4, 5, 6], $cb));
    }

    public function testCollect(): void
    {
        self::assertEquals([1, 2, 3], collect([1, 2, 3])->all());
    }
}
