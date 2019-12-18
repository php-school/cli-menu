<?php

namespace PhpSchool\CliMenu\Style;

use PhpSchool\CliMenu\Terminal\TerminalFactory;
use PhpSchool\Terminal\Terminal;

class SelectableStyle implements ItemStyleInterface
{
    use ItemStyleTrait;

    protected const DEFAULT_STYLES = [
        'fg'            => 'white',
        'bg'            => 'blue',
        'markerOn'      => '● ',
        'markerOff'     => '○ ',
        'itemExtra'     => '✔',
        'displaysExtra' => false,
    ];

    public function __construct(Terminal $terminal = null)
    {
        $this->terminal = $terminal ?: TerminalFactory::fromSystem();

        $this->fg = self::DEFAULT_STYLES['fg'];
        $this->bg = self::DEFAULT_STYLES['bg'];

        $this->generateColoursSetCode();

        $this->setMarkerOn(self::DEFAULT_STYLES['markerOn']);
        $this->setMarkerOff(self::DEFAULT_STYLES['markerOff']);
        $this->setItemExtra(self::DEFAULT_STYLES['itemExtra']);
        $this->setDisplaysExtra(self::DEFAULT_STYLES['displaysExtra']);

        $this->custom = false;
    }

    public function toArray(): array
    {
        return [
            'fg'            => $this->fg,
            'bg'            => $this->bg,
            'markerOn'      => $this->markerOn,
            'markerOff'     => $this->markerOff,
            'itemExtra'     => $this->itemExtra,
            'displaysExtra' => $this->displaysExtra,
        ];
    }

    public function fromArray(array $style) : self
    {
        $this->fg = $style['fg'] ?? $this->fg;
        $this->bg = $style['bg'] ?? $this->bg;
        $this->markerOn = $style['markerOn'] ?? $this->markerOn;
        $this->markerOff = $style['markerOff'] ?? $this->markerOff;
        $this->itemExtra = $style['itemExtra'] ?? $this->itemExtra;
        $this->displaysExtra = $style['displaysExtra'] ?? $this->displaysExtra;

        return $this;
    }
}
