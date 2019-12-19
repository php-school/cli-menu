<?php

namespace PhpSchool\CliMenu\Style;

interface ItemStyleInterface
{
    public function getIsCustom() : bool;

    public function getMarker(bool $selected) : string;

    public function getMarkerOn() : string;

    public function setMarkerOn(string $marker) : self;

    public function getMarkerOff() : string;

    public function setMarkerOff(string $marker) : self;

    public function getItemExtra() : string;

    public function setItemExtra(string $itemExtra) : self;

    public function getDisplaysExtra() : bool;

    public function setDisplaysExtra(bool $displaysExtra) : self;
}
