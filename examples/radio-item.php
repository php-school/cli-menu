<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\RadioStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Select a Language')
    ->addSubMenu('Compiled', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Compiled Languages')
            ->addRadioItem('Rust', $itemCallable)
            ->addRadioItem('C++', $itemCallable)
            ->addRadioItem('Go', $itemCallable)
            ->addRadioItem('Java', $itemCallable)
            ->addRadioItem('C', $itemCallable)
        ;
    })
    ->addSubMenu('Interpreted', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Interpreted Languages')
            ->setRadioStyle(function (RadioStyle $style) {
                $style->setMarkerOff('[ ] ')
                    ->setMarkerOn('[✔] ');
            })
            ->addRadioItem('PHP', $itemCallable)
            ->addRadioItem('Javascript', $itemCallable)
            ->addRadioItem('Ruby', $itemCallable)
            ->addRadioItem('Python', $itemCallable)
        ;
    })
    ->build();

$menu->open();
