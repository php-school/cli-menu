<?php

namespace PhpSchool\CliMenu\Style;

use PhpSchool\CliMenu\Util\ColourUtil;
use PhpSchool\Terminal\Terminal;

trait ItemStyleTrait
{
    /**
     * @var Terminal
     */
    protected $terminal;

    /**
     * @var string
     */
    protected $fg;

    /**
     * @var string
     */
    protected $bg;

    /**
     * @var string
     */
    protected $markerOn;

    /**
     * @var string
     */
    protected $markerOff;

    /**
     * @var string
     */
    protected $itemExtra;

    /**
     * @var bool
     */
    protected $displaysExtra;

    /**
     * @var string
     */
    protected $coloursSetCode;

    protected $custom = false;

    public function getIsCustom() : bool
    {
        return $this->custom;
    }

    /**
     * Get the colour code for Bg and Fg
     */
    public function getColoursSetCode() : string
    {
        return $this->coloursSetCode;
    }

    public function getFg()
    {
        return $this->fg;
    }

    public function setFg(string $fg, string $fallback = null) : self
    {
        $this->custom = true;

        $this->fg = ColourUtil::validateColour(
            $this->terminal,
            $fg,
            $fallback
        );
        $this->generateColoursSetCode();

        return $this;
    }

    public function getBg()
    {
        return $this->bg;
    }

    public function setBg(string $bg, string $fallback = null) : self
    {
        $this->custom = true;

        $this->bg = ColourUtil::validateColour(
            $this->terminal,
            $bg,
            $fallback
        );

        $this->generateColoursSetCode();

        return $this;
    }

    public function getMarker(bool $selected) : string
    {
        return $selected ? $this->markerOn : $this->markerOff;
    }

    public function getMarkerOn() : string
    {
        return $this->markerOn;
    }

    public function setMarkerOn(string $marker) : self
    {
        $this->custom = true;

        $this->markerOn = $marker;

        return $this;
    }

    public function getMarkerOff() : string
    {
        return $this->markerOff;
    }

    public function setMarkerOff(string $marker) : self
    {
        $this->custom = true;

        $this->markerOff = $marker;

        return $this;
    }

    public function getItemExtra() : string
    {
        return $this->itemExtra;
    }

    public function setItemExtra(string $itemExtra) : self
    {
        $this->custom = true;

        $this->itemExtra = $itemExtra;

        return $this;
    }

    public function getDisplaysExtra() : bool
    {
        return $this->displaysExtra;
    }

    public function setDisplaysExtra(bool $displaysExtra) : self
    {
        $this->custom = true;

        $this->displaysExtra = $displaysExtra;

        return $this;
    }

    /**
     * Generates the ansi escape sequence to set the colours
     */
    private function generateColoursSetCode() : void
    {
        if (!ctype_digit($this->fg)) {
            $fgCode = Colour::AVAILABLE_FOREGROUND_COLOURS[$this->fg];
        } else {
            $fgCode = sprintf("38;5;%s", $this->fg);
        }

        if (!ctype_digit($this->bg)) {
            $bgCode = Colour::AVAILABLE_BACKGROUND_COLOURS[$this->bg];
        } else {
            $bgCode = sprintf("48;5;%s", $this->bg);
        }

        $this->coloursSetCode = sprintf("\033[%s;%sm", $fgCode, $bgCode);
    }
}
