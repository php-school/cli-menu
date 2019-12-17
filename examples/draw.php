<?php

use PhpSchool\CliMenu\Action\ExitAction;
use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\SplitItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$cols = 60;
$rows = 20;

$paint = function (CliMenu $menu) {
    $item = $menu->getSelectedItem();
    if ($item->getText() === ' ') {
        $item->setText('█');
    } else {
        $item->setText(' ');
    }
};

$clear = function (CliMenu $menu) {
    foreach ($menu->getItems() as $item) {
        if ($item instanceof SplitItem && $item->canSelect()) {
            foreach ($item->getItems() as $cell) {
                if ($cell->canSelect() && $cell->getText() !== ' ') {
                    $cell->setText(' ');
                }
            }
        }
    }
    $menu->redraw(false);
};

$builder = (new CliMenuBuilder)
    ->disableDefaultItems()
    ->setWidth($cols + 10)
    ->setBorder(0)
    ->setMargin(2)
    ->setPadding(2, 5)
    ->setSelectedMarker('')
    ->setUnselectedMarker('')
    ->addAsciiArt('Draw your own art !')
    ->addLineBreak();

for ($i = 0; $i < $rows; $i++) {
    $builder->addSplitItem(function(SplitItemBuilder $b) use ($cols, $paint) {
        $b->setGutter(0);
        for ($j = 0; $j < $cols; $j++) {
            $b->addItem(' ', $paint);
        }
    });
}

$builder->addSplitItem(function(SplitItemBuilder $b) {
    $b->addStaticItem('Enter: Toggle draw')
        ->addStaticItem('C: Clear screen')
        ->addStaticItem('X: Exit');
});

$menu = $builder->build();

$menu->addCustomControlMappings(['C' => $clear, 'X' => new ExitAction]);
$menu->open();
