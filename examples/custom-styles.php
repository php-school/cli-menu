<?php
declare(strict_types=1);

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\Style\SelectableStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Styling')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->setWidth(70)
    ->setBackgroundColour('yellow')
    ->setForegroundColour('black')
    ->setPadding(4)
    ->setMargin(4)
    ->setBorder(1, 2, 'red')
    ->setTitleSeparator('- ')
    ->modifySelectableStyle(function (SelectableStyle $style) {
        $style->setUnselectedMarker(' ')
            ->setSelectedMarker('>');
    })
    ->build();

$menu->open();
