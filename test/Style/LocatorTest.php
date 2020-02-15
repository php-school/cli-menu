<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Style;

use PhpSchool\CliMenu\Style\CheckboxStyle;
use PhpSchool\CliMenu\Style\DefaultStyle;
use PhpSchool\CliMenu\Style\Locator;
use PhpSchool\CliMenu\Style\RadioStyle;
use PhpSchool\CliMenu\Style\SelectableStyle;
use PHPUnit\Framework\TestCase;

class LocatorTest extends TestCase
{
    public function testImportIgnoresOtherWhenStylesModified() : void
    {
        $locator = new Locator();

        $defaultStyle = $locator->getStyle(DefaultStyle::class);
        $selectableStyle = $locator->getStyle(SelectableStyle::class);
        $checkboxStyle = $locator->getStyle(CheckboxStyle::class);
        $radioStyle = $locator->getStyle(RadioStyle::class);

        $selectableStyle->setUnselectedMarker('[ ]');
        $selectableStyle->setSelectedMarker('[X]');

        $checkboxStyle->setCheckedMarker('[*] ');
        $radioStyle->setCheckedMarker('[*] ');

        $otherLocator = new Locator();

        $locator->importFrom($otherLocator);

        self::assertSame($defaultStyle, $locator->getStyle(DefaultStyle::class));
        self::assertSame($selectableStyle, $locator->getStyle(SelectableStyle::class));
        self::assertSame($checkboxStyle, $locator->getStyle(CheckboxStyle::class));
        self::assertSame($radioStyle, $locator->getStyle(RadioStyle::class));
    }

    public function testImportStylesWhenOneStyleNotModified() : void
    {
        $locator = new Locator();

        $defaultStyle = $locator->getStyle(DefaultStyle::class);
        $selectableStyle = $locator->getStyle(SelectableStyle::class);
        $checkboxStyle = $locator->getStyle(CheckboxStyle::class);
        $radioStyle = $locator->getStyle(RadioStyle::class);

        $checkboxStyle->setCheckedMarker('[*] ');
        $radioStyle->setCheckedMarker('[*] ');

        $otherLocator = new Locator();

        $locator->importFrom($otherLocator);

        self::assertSame($defaultStyle, $locator->getStyle(DefaultStyle::class));
        self::assertSame($checkboxStyle, $locator->getStyle(CheckboxStyle::class));
        self::assertSame($radioStyle, $locator->getStyle(RadioStyle::class));

        self::assertNotSame($selectableStyle, $locator->getStyle(SelectableStyle::class));
        self::assertSame($otherLocator->getStyle(SelectableStyle::class), $locator->getStyle(SelectableStyle::class));
    }

    public function testImportStylesWhenStyleNotModified() : void
    {
        $locator = new Locator();

        $selectableStyle = $locator->getStyle(SelectableStyle::class);
        $checkboxStyle = $locator->getStyle(CheckboxStyle::class);
        $radioStyle = $locator->getStyle(RadioStyle::class);

        $otherLocator = new Locator();

        $locator->importFrom($otherLocator);

        self::assertNotSame($selectableStyle, $locator->getStyle(SelectableStyle::class));
        self::assertNotSame($checkboxStyle, $locator->getStyle(CheckboxStyle::class));
        self::assertNotSame($radioStyle, $locator->getStyle(RadioStyle::class));

        self::assertSame($otherLocator->getStyle(SelectableStyle::class), $locator->getStyle(SelectableStyle::class));
        self::assertSame($otherLocator->getStyle(CheckboxStyle::class), $locator->getStyle(CheckboxStyle::class));
        self::assertSame($otherLocator->getStyle(RadioStyle::class), $locator->getStyle(RadioStyle::class));
    }
}
