<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\ToggleableItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    /** @var ToggleableItem $item */
    $item = $menu->getSelectedItem();

    $item->setToggled(!$item->getToggled());

    $menu->redraw();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Select a Language')
    ->addSubMenu('Compiled', function (CliMenuBuilder $b) use($itemCallable) {
        $b->setTitle('Compiled Languages')
            ->addToggleableItem('Rust', $itemCallable)
            ->addToggleableItem('C++', $itemCallable)
            ->addToggleableItem('Go', $itemCallable)
            ->addToggleableItem('Java', $itemCallable)
            ->addToggleableItem('C', $itemCallable)
        ;
    })
    ->addSubMenu('Interpreted', function (CliMenuBuilder $b) use($itemCallable) {
        $b->setTitle('Interpreted Languages')
            ->setUntoggledMarker('[ ]')
            ->setToggledMarker('[✔]')
            ->addToggleableItem('PHP', $itemCallable)
            ->addToggleableItem('Javascript', $itemCallable)
            ->addToggleableItem('Ruby', $itemCallable)
            ->addToggleableItem('Python', $itemCallable)
        ;
    })
    ->build();

$menu->open();
