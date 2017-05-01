<?php
namespace PhpSchool\CliMenu\MenuItem\Helper;

use PhpSchool\CliMenu\MenuItem\AsciiArtItem;

class AsciiArtItemJustificationHelper
{
    private $contentWidth;
    private $artLength;
    private $position;

    public function  __construct($position, $contentWidth, $artLength)
    {
        $this->contentWidth = $contentWidth;
        $this->artLength = $artLength;
        $this->position = $position;
    }

    public function justifyRow($row)
    {
        switch ($this->position) {
            case AsciiArtItem::POSITION_LEFT:
                return $this->leftJustifyRow($row);
            case AsciiArtItem::POSITION_RIGHT:
                return $this->rightJustifyRow($row);
            case AsciiArtItem::POSITION_CENTER:
            default:
                return $this->centerJustifyRow($row);
        }
    }

    /**
     * @param $row
     * @return string
     */
    private function leftJustifyRow($row)
    {
        return $row;
    }

    /**
     * @param $row
     * @return string
     */
    private function rightJustifyRow($row)
    {
        $padding = $this->_getPadding($row);
        $row = rtrim($row);
        $padding = $padding - ($this->artLength - mb_strlen($row));
        return sprintf('%s%s', str_repeat(' ', $padding), $row);
    }

    /**
     * @param $row
     * @return string
     */
    private function centerJustifyRow($row)
    {
        $padding = $this->_getPadding($row);
        $row = rtrim($row);
        $padding = $padding - ($this->artLength - mb_strlen($row));
        $left = ceil($padding / 2);
        $right = $padding - $left;
        return sprintf('%s%s%s', str_repeat(' ', $left), $row, str_repeat(' ', $right));
    }

    /**
     * @param $row
     * @return int
     */
    private function _getPadding($row)
    {
        $length = mb_strlen($row);
        return $this->contentWidth - $length;
    }
}