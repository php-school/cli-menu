<?php

declare(strict_types=1);

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\MenuItem\SelectableItemRenderer;
use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\SelectableStyle;
use PhpSchool\CliMenuTest\MockTerminal;
use PHPUnit\Framework\TestCase;

class SelectableItemRendererTest extends TestCase
{
    public function testRender() : void
    {
        $renderer = new SelectableItemRenderer();

        $menuStyle = new MenuStyle(new MockTerminal);
        $menuStyle->setWidth(35);

        $style = (new SelectableStyle())->setItemExtra('[DONE]');

        $item = new SelectableItem('SOME TEXT', function () {
        });
        $item->setStyle($style);
        $item->showItemExtra();

        self::assertEquals(
            [
                '● SOME TEXT              [DONE]',
            ],
            $renderer->render($menuStyle, $item, true, false)
        );
    }
    public function testRenderMultiLine() : void
    {
        $renderer = new SelectableItemRenderer();

        $menuStyle = new MenuStyle(new MockTerminal);
        $menuStyle->setWidth(35);
        $style = (new SelectableStyle())->setItemExtra('[DONE]');

        $item = new SelectableItem('SOME TEXT THAT IS MUCH LONGER THAN THE AVAILABLE WIDTH', function () {
        });
        $item->setStyle($style);
        $item->showItemExtra();

        self::assertEquals(
            [
                '● SOME TEXT THAT IS      [DONE]',
                '  MUCH LONGER THAN THE',
                '  AVAILABLE WIDTH',
            ],
            $renderer->render($menuStyle, $item, true, false)
        );
    }
    public function testRenderUnselected() : void
    {
        $renderer = new SelectableItemRenderer();

        $menuStyle = new MenuStyle(new MockTerminal);
        $menuStyle->setWidth(35);
        $style = (new SelectableStyle())->setItemExtra('[DONE]');

        $item = new SelectableItem('SOME TEXT', function () {
        });
        $item->setStyle($style);
        $item->showItemExtra();

        self::assertEquals(
            [
                '○ SOME TEXT              [DONE]',
            ],
            $renderer->render($menuStyle, $item, false, false)
        );
    }
    public function testRenderDisabled() : void
    {
        $renderer = new SelectableItemRenderer();

        $menuStyle = new MenuStyle(new MockTerminal);
        $menuStyle->setWidth(35);
        $style = (new SelectableStyle())->setItemExtra('[DONE]');

        $item = new SelectableItem('SOME TEXT', function () {
        });
        $item->setStyle($style);
        $item->showItemExtra();

        self::assertEquals(
            [
                "\033[2m● SOME TEXT\033[22m              [DONE]",
            ],
            $renderer->render($menuStyle, $item, true, true)
        );
    }
    public function testWrapAndIndentText() : void
    {
        $renderer = new SelectableItemRenderer();

        $text = 'SOME TEXT THAT IS MUCH LONGER THAN THE AVAILABLE WIDTH';

        self::assertEquals(
            [
                '[ ] SOME TEXT THAT',
                '    IS MUCH LONGER THAN',
                '    THE AVAILABLE WIDTH',
            ],
            $renderer->wrapAndIndentText('[ ] ', $text, 20)
        );
    }
    public function testLineWithExtra() : void
    {
        $renderer = new SelectableItemRenderer();
        $style = (new SelectableStyle())->setItemExtra('[DONE]');

        self::assertEquals(
            'FIRST LINE       [DONE]',
            $renderer->lineWithExtra('FIRST LINE', 15, $style)
        );
    }
    public function testEmptyString() : void
    {
        $renderer = new SelectableItemRenderer();

        self::assertEquals(' ', $renderer->emptyString(1));
        self::assertEquals('   ', $renderer->emptyString(3));
        self::assertEquals('     ', $renderer->emptyString(5));
    }
    public function testGetAvailableTextWidthWithoutExtra() : void
    {
        $renderer = new SelectableItemRenderer();

        $menuStyle = new MenuStyle(new MockTerminal);
        $menuStyle->setWidth(100);

        $itemStyle = new SelectableStyle();

        self::assertEquals(92, $renderer->getAvailableTextWidth($menuStyle, $itemStyle));
    }
    public function testGetAvailableTextWidthWithExtra() : void
    {
        $renderer = new SelectableItemRenderer();

        $menuStyle = new MenuStyle(new MockTerminal);
        $menuStyle->setWidth(100);

        $itemStyle = new SelectableStyle();
        $itemStyle->setItemExtra('[DONE]');

        self::assertEquals(84, $renderer->getAvailableTextWidth($menuStyle, $itemStyle));
    }
}
