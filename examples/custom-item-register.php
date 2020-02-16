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

$myItem = new MyItem('MY CUSTOM ITEM 1', $itemCallable);
$myItem2 = new MyItem('MY CUSTOM ITEM 2', $itemCallable);

$menu = (new CliMenuBuilder)
    ->registerItemStyle(MyItem::class, new MySelectableStyle())
    ->modifyStyle(MySelectableStyle::class, function (MySelectableStyle $style) {
        $style->setUnselectedMarker('--- ');
        $style->setSelectedMarker('*** ');
    })
    ->setTitle('Showcasing Custom Items & Styles')
    ->addMenuItem($myItem)
    ->addMenuItem($myItem2)
    ->addLineBreak()
    ->addSplitItem(function (SplitItemBuilder $b) use ($itemCallable, $myItem) {
        $b->addItem('Split Item', $itemCallable);
        $b->addSubMenu('Split Item Submenu', function (CliMenuBuilder $b) use ($myItem) {
            $b->addMenuItem($myItem);
        });
        $b->addMenuItem($myItem);
    })
    ->addLineBreak()
    ->addSubMenu('Options', function (CliMenuBuilder $b) use ($myItem) {
        $b->addMenuItem($myItem);
    })
    ->build();

$menu->open();
