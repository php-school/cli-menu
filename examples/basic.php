<?php

use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\MenuItem\SelectableItem;
use PhpSchool\CliMenu\Style\SelectableStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

class MyItem extends SelectableItem {

};

class MySelectableStyle extends SelectableStyle {

}

$myItem = new MyItem('YO', $itemCallable);
$myItem2 = new MyItem('YO2', $itemCallable);

$menu = (new CliMenuBuilder)
    ->registerItemStyle(MyItem::class, new MySelectableStyle())
//    ->modifyStyle(MySelectableStyle::class, function (MySelectableStyle $style) {
//        $style->setUnselectedMarker('[#]');
//    })
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->addMenuItem($myItem)
    ->addMenuItem($myItem2)
    ->addSubMenu('OPT', function (CliMenuBuilder $b) use ($myItem) {
        $b->addMenuItem($myItem);
    })
    ->addSplitItem(function (SplitItemBuilder $b) use ($itemCallable, $myItem) {
        $b->addItem('FIRST SPLIT', $itemCallable);
        $b->addSubMenu('SUB', function (CliMenuBuilder $b) use ($myItem) {
            $b->addMenuItem($myItem);
        });
        $b->addMenuItem($myItem);
    })
    ->build();

$menu->open();
