<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Util;

use PhpSchool\CliMenu\Util\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $result = (new Collection([1, 2, 3, 4, 5, 6]))
            ->filter(function ($k, $v) {
                return $v > 3;
            })
            ->values()
            ->map(function ($k, $v) {
                return $v * 2;
            })
            ->all();

        self::assertEquals([8, 10, 12], $result);
    }
}
