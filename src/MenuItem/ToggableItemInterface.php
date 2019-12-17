<?php

namespace PhpSchool\CliMenu\MenuItem;

interface ToggableItemInterface
{
    /**
     * Flip checked state
     */
    public function toggle() : void;

    /**
     * Sets checked state to true
     */
    public function setChecked() : void;

    /**
     * Sets checked state to false
     */
    public function setUnchecked() : void;

    /**
     * Gets checked state
     *
     * @return bool
     */
    public function getChecked() : bool;
}
