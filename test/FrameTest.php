<?php

namespace PhpSchool\CliMenuTest;

use PhpSchool\CliMenu\Frame;
use PHPUnit_Framework_TestCase;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class FrameTest extends PHPUnit_Framework_TestCase
{
    public function testNewLine()
    {
        $frame = new Frame;
        $frame->newLine();

        $this->assertEquals(["\n"], $frame->getRows());

        $frame = new Frame;
        $frame->newLine(1);

        $this->assertEquals(["\n"], $frame->getRows());

        $frame = new Frame;
        $frame->newLine(2);

        $this->assertEquals(["\n", "\n"], $frame->getRows());
    }

    public function testAddRows()
    {
        $frame = new Frame;
        $frame->addRows(['one', 'two']);
        $this->assertEquals(['one', 'two'], $frame->getRows());
        $frame->addRows(['three']);
        $this->assertEquals(['one', 'two', 'three'], $frame->getRows());
    }

    public function testAddRow()
    {
        $frame = new Frame;
        $frame->addRow('one');
        $this->assertEquals(['one'], $frame->getRows());
        $frame->addRow('two');
        $this->assertEquals(['one', 'two'], $frame->getRows());
    }

    public function testCount()
    {
        $frame = new Frame;
        $frame->addRow('one');
        $this->assertEquals(['one'], $frame->getRows());
        $this->assertCount(1, $frame);
        $frame->addRow('two');
        $this->assertEquals(['one', 'two'], $frame->getRows());
        $this->assertCount(2, $frame);
    }

    public function testAll()
    {
        $frame = new Frame;
        $frame->addRow('one');
        $frame->addRows(["two", "three"]);
        $frame->newLine(2);

        $this->assertEquals(['one', 'two', 'three', "\n", "\n"], $frame->getRows());
        $this->assertCount(5, $frame);
    }
}
