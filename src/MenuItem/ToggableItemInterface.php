<?php

namespace PhpSchool\CliMenu\MenuItem;

interface ToggableItemInterface
{
    /**
     * Gets checked state
     */
    public function getChecked() : bool;

    /**
     * Sets checked state to true
     */
    public function setChecked() : void;

    /**
     * Sets checked state to false
     */
    public function setUnchecked() : void;

    /**
     * Flip checked state
     */
    public function toggle() : void;
}
