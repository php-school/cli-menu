<?php

namespace PhpSchool\CliMenu\Style;

interface ItemStyleInterface
{
    public function getIsCustom() : bool;

    public function getMarker(bool $selected) : string;

    public function getMarkerOn() : string;

    /**
     * @return $this
     */
    public function setMarkerOn(string $marker);

    public function getMarkerOff() : string;

    /**
     * @return $this
     */
    public function setMarkerOff(string $marker);

    public function getItemExtra() : string;

    /**
     * @return $this
     */
    public function setItemExtra(string $itemExtra);

    public function getDisplaysExtra() : bool;

    /**
     * @return $this
     */
    public function setDisplaysExtra(bool $displaysExtra);
}
