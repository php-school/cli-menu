<?php

use PhpSchool\CliMenu\Builder\SplitItemBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setWidth(150)
    ->addSplitItem(function (SplitItemBuilder $b) use ($itemCallable) {
        $b->addSubMenu('Sub Menu on a split item', function (CliMenuBuilder $b) {
            $b->setTitle('Behold the awesomeness')
                ->addItem('This is awesome', function() { print 'Yes!'; })
                ->addSplitItem(function (SplitItemBuilder $b) {
                    $b->addItem('Split Item 1', function() { print 'Item 1!'; })
                        ->addItem('Split Item 2', function() { print 'Item 2!'; })
                        ->addItem('Split Item 3', function() { print 'Item 3!'; })
                        ->addSubMenu('Split Item Nested Sub Menu', function (CliMenuBuilder $b) {
                            $b->addItem('One', function() { print 'One!'; })
                                ->addItem('Two', function() { print 'Two!'; })
                                ->addItem('Three', function() { print 'Three!'; });
                        });
                });
        })
        ->addItem('Item 2', $itemCallable)
        ->addStaticItem('Item 3 - Static')
        ->addItem('Item 4', $itemCallable);
    })
    ->build();
   

$menu->open();