<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    public function testMapWithStringKeys() : void
    {
        self::assertEquals(
            ['one' => 1, 'two' => 4, 'three' => 9],
            ArrayUtil::mapWithKeys(
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
            ArrayUtil::mapWithKeys(
                [1, 2, 3],
                function (string $key, int $num) {
                    return $num * $num;
                }
            )
        );
    }
}
