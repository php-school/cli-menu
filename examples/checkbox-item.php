<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\CheckboxStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Select a Language')
    ->addSubMenu('Compiled', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Compiled Languages')
            ->addCheckboxItem('Rust', $itemCallable)
            ->addCheckboxItem('C++', $itemCallable)
            ->addCheckboxItem('Go', $itemCallable)
            ->addCheckboxItem('Java', $itemCallable)
            ->addCheckboxItem('C', $itemCallable)
        ;
    })
    ->addSubMenu('Interpreted', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Interpreted Languages')
            ->modifyCheckboxStyle(function (CheckboxStyle $style) {
                $style->setUncheckedMarker('[○] ')
                    ->setCheckedMarker('[●] ');
            })
            ->addCheckboxItem('PHP', $itemCallable)
            ->addCheckboxItem('Javascript', $itemCallable)
            ->addCheckboxItem('Ruby', $itemCallable)
            ->addCheckboxItem('Python', $itemCallable)
        ;
    })
    ->build();

$menu->open();
