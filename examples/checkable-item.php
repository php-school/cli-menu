<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\CheckableStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Select a Language')
    ->addSubMenu('Compiled', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Compiled Languages')
            ->addCheckableItem('Rust', $itemCallable)
            ->addCheckableItem('C++', $itemCallable)
            ->addCheckableItem('Go', $itemCallable)
            ->addCheckableItem('Java', $itemCallable)
            ->addCheckableItem('C', $itemCallable)
        ;
    })
    ->addSubMenu('Interpreted', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Interpreted Languages')
            ->setCheckableStyle(function (CheckableStyle $style) {
                $style->setMarkerOff('[○] ')
                    ->setMarkerOn('[●] ');
            })
            ->addCheckableItem('PHP', $itemCallable)
            ->addCheckableItem('Javascript', $itemCallable)
            ->addCheckableItem('Ruby', $itemCallable)
            ->addCheckableItem('Python', $itemCallable)
        ;
    })
    ->build();

$menu->open();
