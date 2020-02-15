<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\Style;

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;
use PhpSchool\CliMenu\MenuItem\LineBreakItem;
use PhpSchool\CliMenu\MenuItem\MenuItemInterface;
use PhpSchool\CliMenu\MenuItem\MenuMenuItem;
use PhpSchool\CliMenu\MenuItem\RadioItem;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\StaticItem;
use PhpSchool\CliMenu\Style\CheckboxStyle;
use PhpSchool\CliMenu\Style\DefaultStyle;
use PhpSchool\CliMenu\Style\Exception\InvalidStyle;
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

    public function testGetStyleForMenuItemThrowsExceptionIfItemNotRegistered() : void
    {
        self::expectException(InvalidStyle::class);

        $myItem = new class extends LineBreakItem {
        };

        $locator = new Locator();
        $locator->getStyleForMenuItem($myItem);
    }

    public function itemStyleProvider() : array
    {
        $action = function () {
        };

        return [
            [DefaultStyle::class, new LineBreakItem()],
            [DefaultStyle::class, new StaticItem('*')],
            [DefaultStyle::class, new AsciiArtItem('*')],
            [SelectableStyle::class, new SelectableItem('1', $action)],
            [SelectableStyle::class, new MenuMenuItem('2', new CliMenu('sub', []))],
            [CheckboxStyle::class, new CheckboxItem('3', $action)],
            [RadioStyle::class, new RadioItem('4', $action)],
        ];
    }

    /**
     * @dataProvider itemStyleProvider
     */
    public function testGetStyleForMenuItem(string $expectedStyleClass, MenuItemInterface $menuItem) : void
    {
        $locator = new Locator();

        self::assertInstanceOf($expectedStyleClass, $locator->getStyleForMenuItem($menuItem));
    }

    public function testGetStyleThrowsExceptionIfStyleClassNotRegistered() : void
    {
        self::expectException(InvalidStyle::class);

        $locator = new Locator();
        $locator->getStyle('NonExistingStyleClass');
    }

    public function styleProvider() : array
    {
        return [
            [DefaultStyle::class],
            [SelectableStyle::class],
            [SelectableStyle::class],
            [CheckboxStyle::class],
            [RadioStyle::class],
        ];
    }

    /**
     * @dataProvider styleProvider
     */
    public function testGetStyle(string $styleClass) : void
    {
        $locator = new Locator();

        self::assertInstanceOf($styleClass, $locator->getStyle($styleClass));
    }

    public function testSetStyleThrowsExceptionIfStyleClassNotRegistered() : void
    {
        self::expectException(InvalidStyle::class);

        $locator = new Locator();
        $locator->setStyle(new DefaultStyle(), 'NonExistingStyleClass');
    }

    public function testSetStyleThrowsExceptionIfStyleNotInstanceOfStyleClass() : void
    {
        self::expectException(InvalidStyle::class);

        $invalidStyle = new class extends SelectableStyle {
        };

        $locator = new Locator();
        $locator->setStyle($invalidStyle, DefaultStyle::class);
    }

    public function testSetStyle() : void
    {
        $locator = new Locator();

        $locator->setStyle($new = new DefaultStyle(), DefaultStyle::class);

        self::assertSame($new, $locator->getStyle(DefaultStyle::class));
    }
}
