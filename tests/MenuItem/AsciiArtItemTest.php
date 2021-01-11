<?php

namespace PhpSchool\CliMenuTest\MenuItem;

use PhpSchool\CliMenu\MenuItem\AsciiArtItem;
use PhpSchool\CliMenu\MenuStyle;
use PHPUnit\Framework\TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class AsciiArtItemTest extends TestCase
{
    public function testCanSelectIsFalse() : void
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertFalse($item->canSelect());
    }

    public function testGetSelectActionReturnsNull() : void
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertNull($item->getSelectAction());
    }

    public function testShowsItemExtraReturnsFalse() : void
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetText() : void
    {
        $item = new AsciiArtItem('////\\\\');
        $this->assertEquals('////\\\\', $item->getText());
    }

    public function testGetArtLength() : void
    {
        $item = new AsciiArtItem("//\n//\n///");
        $this->assertEquals(3, $item->getArtLength());
    }

    public function testGetRowsLeftAligned() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_LEFT);
        $this->assertEquals(
            [
                "//",
                "//",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsRightAligned() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_RIGHT);
        $this->assertEquals(
            [
                "        //",
                "        //",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsCenterAligned() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_CENTER);
        $this->assertEquals(
            [
                "    //",
                "    //",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testGetRowsCenterAlignedWithOddWidth() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(11));

        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_CENTER);
        $this->assertEquals(
            [
                "     //",
                "     //",
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testHideAndShowItemExtraHasNoEffect() : void
    {
        $item = new AsciiArtItem("//\n//", AsciiArtItem::POSITION_CENTER);

        $this->assertFalse($item->showsItemExtra());
        $item->showItemExtra();
        $this->assertFalse($item->showsItemExtra());
        $item->hideItemExtra();
        $this->assertFalse($item->showsItemExtra());
    }

    public function testGetRowsReturnsStaticAltItemWhenWidthIsTooSmall() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(10));
        
        $item = new AsciiArtItem('TOO LONG. SO SO LONG.', AsciiArtItem::POSITION_CENTER, 'my alt');
        
        self::assertSame(['my alt'], $item->getRows($menuStyle));
    }

    public function testGetRowsDoesNotReturnsStaticAltItemWhenOnlySpacesOverflow() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(15));
        
        $item = new AsciiArtItem('NOT TOO LONG            ', AsciiArtItem::POSITION_LEFT, 'my alt');
        
        self::assertSame(['NOT TOO LONG'], $item->getRows($menuStyle));
    }

    public function testWithRealAsciiArtCenterAligned() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $art = <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|
      PHP SCHOOL
LEARNING FOR ELEPHANTS
ART;

        $item = new AsciiArtItem($art, AsciiArtItem::POSITION_CENTER);
        $this->assertEquals(
            [
                '            _ __ _',
                '           / |..| \\',
                '           \/ || \/',
                "            |_''_|",
                '          PHP SCHOOL',
                '    LEARNING FOR ELEPHANTS'
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testWithRealAsciiArtRightAligned() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));

        $art = <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|
      PHP SCHOOL
LEARNING FOR ELEPHANTS
ART;

        $item = new AsciiArtItem($art, AsciiArtItem::POSITION_RIGHT);
        $this->assertEquals(
            [
                '                _ __ _',
                '               / |..| \\',
                '               \/ || \/',
                "                |_''_|",
                '              PHP SCHOOL',
                '        LEARNING FOR ELEPHANTS'
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testWithRealAsciiArtCenterAlignedWithWhiteSpaceAtTheEndOfEachLine() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));
        
        $art = <<<ART
        _ __ _        
       / |..| \       
       \/ || \/       
        |_''_|        
      PHP SCHOOL      
LEARNING FOR ELEPHANTS
ART;
        $item = new AsciiArtItem($art, AsciiArtItem::POSITION_CENTER);
        $this->assertEquals(
            [
                '            _ __ _',
                '           / |..| \\',
                '           \/ || \/',
                "            |_''_|",
                '          PHP SCHOOL',
                '    LEARNING FOR ELEPHANTS'
            ],
            $item->getRows($menuStyle)
        );
    }

    public function testSetText() : void
    {
        $menuStyle = $this->createMock(MenuStyle::class);

        $menuStyle
            ->expects($this->any())
            ->method('getContentWidth')
            ->will($this->returnValue(30));
        
        $art = <<<ART
        _ __ _        
       / |..| \       
       \/ || \/       
        |_''_|        
      PHP SCHOOL      
LEARNING FOR ELEPHANTS
ART;
        $item = new AsciiArtItem("//", AsciiArtItem::POSITION_CENTER);
        $item->setText($art);

        $this->assertEquals(
            [
                '            _ __ _',
                '           / |..| \\',
                '           \/ || \/',
                "            |_''_|",
                '          PHP SCHOOL',
                '    LEARNING FOR ELEPHANTS'
            ],
            $item->getRows($menuStyle)
        );
    }
}
