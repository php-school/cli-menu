<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\CliMenuBuilder;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuStyle;

require_once(__DIR__ . '/../vendor/autoload.php');

$subMenu = (new CliMenuBuilder('CLI Menu with Submenu > Options'))
    ->addItem(new MenuItem('Option 1'))
    ->addItem(new MenuItem('Option 2'))
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->setMenuStyle(new MenuStyle('red'))
    ->build();

$menu = (new CliMenuBuilder('CLI Menu with Submenu'))
    ->addItem(new MenuItem('First Item'))
    ->addItem(new MenuItem('Second Item'))
    ->addItem(new MenuItem('Third Item'))
    ->setItemCallback(function (CliMenu $menu) {
        echo sprintf('You selected "%s"', $menu->getSelectedItem()->getText());
    })
    ->addSubMenuAsAction('Options', $subMenu)
    ->build();

$menu->display();
