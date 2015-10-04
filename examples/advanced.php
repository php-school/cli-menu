<?php

use MikeyMike\CliMenu\CliMenu;
use MikeyMike\CliMenu\MenuItem\MenuItem;
use MikeyMike\CliMenu\MenuItem\SelectableItem;

require_once(__DIR__ . '/../vendor/autoload.php');

/**
 * Class AdvancedMenu
 *
 * We could go crazy, there really isn't much of a limit!
 */
class AdvancedMenu {

    /**
     * @var CliMenu
     */
    private $mainMenu;

    /**
     * @var CliMenu
     */
    private $optionsMenu;

    /**
     * Contruct the advanced menu
     */
    public function __construct()
    {
        $this->mainMenu = new CliMenu(
            'Advanced CLI Menu',
            [
                new MenuItem('First Item'),
                new SelectableItem('Custom function', function (CliMenu $menu) {
                    echo "Awesome custom function!";
                }),
                new MenuItem('Third Item')
            ],
            function (CliMenu $menu) {
                echo sprintf(
                    "%s",
                    $menu->getSelectedItem()->getText()
                );
            },
            [
                new SelectableItem('Options', [$this, 'showOptionsMenu'])
            ]
        );

        $this->optionsMenu = new CliMenu(
            'Advanced CLI Menu > Options',
            [
                new SelectableItem('Have some custom actioned items here', function (CliMenu $menu) {
                    echo "You could do all sorts on this";
                }),
                new MenuItem("Or group these options into the standard menu item action"),
                new MenuItem("Because no one likes repeating things")
            ],
            function (CliMenu $menu) {
                echo "Default action here";
            },
            [
                new SelectableItem('Go Back', [$this, 'showMainMenu'])
            ],
            null,
            new \MikeyMike\CliMenu\MenuStyle('red')
        );
    }

    /**
     * Show the options menu
     *
     * @param CliMenu $menu
     */
    public function showOptionsMenu(CliMenu $menu)
    {
        $this->optionsMenu->display();
    }

    /**
     * @param CliMenu $menu
     */
    public function showMainMenu(CliMenu $menu)
    {
        $this->mainMenu->display();
    }

    /**
     * Show the advanced menu
     */
    public function display()
    {
        $this->mainMenu->display();
    }
}

$menu = new AdvancedMenu();
$menu->display();
