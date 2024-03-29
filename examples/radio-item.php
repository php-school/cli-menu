<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\RadioStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Select a Language')
    ->addSubMenu('Compiled', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Compiled Languages')
            ->addRadioItem('Rust', $itemCallable)
            ->addRadioItem('Go', $itemCallable)
            ->addRadioItem('Java', $itemCallable)
            ->addRadioItems([
                ['C++', $itemCallable],
                ['C', $itemCallable]
            ])
        ;
    })
    ->addSubMenu('Interpreted', function (CliMenuBuilder $b) use ($itemCallable) {
        $b->setTitle('Interpreted Languages')
            ->modifyRadioStyle(function (RadioStyle $style) {
                $style->setUncheckedMarker('[ ] ')
                    ->setCheckedMarker('[✔] ');
            })
            ->addRadioItem('PHP', $itemCallable)
            ->addRadioItem('Javascript', $itemCallable)
            ->addRadioItem('Ruby', $itemCallable)
            ->addRadioItem('Python', $itemCallable)
        ;
    })
    ->build();

$menu->open();
