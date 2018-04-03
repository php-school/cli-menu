<p align="center">
    <img src="https://cloud.githubusercontent.com/assets/2174476/10601666/071e3e24-770b-11e5-9cba-8ae6402ff550.gif" width="300" />
</p>

<p align="center">
    <a href="https://travis-ci.org/php-school/cli-menu" title="Build Status" target="_blank">
     <img src="https://img.shields.io/travis/php-school/cli-menu/master.svg?style=flat-square&label=Linux" />
    </a>
    <a href="https://ci.appveyor.com/project/mikeymike/cli-menu" title="Windows Build Status" target="_blank">
     <img src="https://img.shields.io/appveyor/ci/mikeymike/cli-menu/master.svg?style=flat-square&label=Windows" />
    </a>
    <a href="https://codecov.io/github/php-school/cli-menu" title="Coverage Status" target="_blank">
     <img src="https://img.shields.io/codecov/c/github/php-school/cli-menu.svg?style=flat-square" />
    </a>
    <a href="https://scrutinizer-ci.com/g/php-school/cli-menu/" title="Scrutinizer Code Quality" target="_blank">
     <img src="https://img.shields.io/scrutinizer/g/php-school/cli-menu.svg?style=flat-square" />
    </a>
    <a href="https://phpschool-team.slack.com/messages">
      <img src="https://phpschool.herokuapp.com/badge.svg">
    </a>
</p>

---
## Contents

  * [Installation](#installation)
  * [Usage](#usage)
    * [Quick Setup](#quick-setup)
    * [Examples](#examples)
  * [API](#api)
  * [Docs Translations](#docs-translations)
  * [Integrations](#integrations)


### Installation

```bash
composer require php-school/cli-menu
```

### Usage

#### Quick Setup
Here is a super basic example menu which will echo out the text of the selected item to get you started.
```php
<?php

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```


#### Examples

Check out the [examples](examples) directory and run them to check out what is possible! 

##### Basic Menu 
<img width="600" alt="basic" src="https://cloud.githubusercontent.com/assets/2817002/11442386/cb0e41a2-950c-11e5-8dd6-913aeab1632a.png">

##### Basic Menu with separation
<img width="600" alt="basic-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442388/cdece950-950c-11e5-8128-4f849a1aea9f.png">

##### Menu with crazy separation
<img width="600" alt="crazy-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442389/d04627fc-950c-11e5-8c80-f82b8fe3f5da.png">

##### Custom Styles
<img width="600" alt="custom-styles" src="https://cloud.githubusercontent.com/assets/2817002/11442391/d3d72d1c-950c-11e5-9698-c2aeec002b24.png">

##### Useful Separation
<img width="600" alt="useful-seperation" src="https://cloud.githubusercontent.com/assets/2817002/11442393/d862c72e-950c-11e5-8cbc-d8c73899627a.png">

##### Item Extra
<img width="600" alt="item-extra" src="https://cloud.githubusercontent.com/assets/2817002/11442395/dfe460f2-950c-11e5-9aed-9bc9c91b7ea6.png">

##### Remove Defaults
<img width="600" alt="remove-defaults" src="https://cloud.githubusercontent.com/assets/2817002/11442399/e3e8b8a6-950c-11e5-8dad-fdd4db93b850.png">

##### Submenu
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/11442401/e6f03ef2-950c-11e5-897a-6d55496a4105.png">
<img width="600" alt="submenu-options" src="https://cloud.githubusercontent.com/assets/2817002/11442403/eaf4782e-950c-11e5-82c5-ab57f84cd6bc.png">

##### Disabled Items & Submenus
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2174476/19047849/868fa8c0-899b-11e6-9004-811c8da6d435.png">

##### Flash Dialogue
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/19786090/1f07dad6-9c94-11e6-91b0-c20ab2e6e27d.png">

##### Confirm Dialogue
<img width="600" alt="submenu" src="https://cloud.githubusercontent.com/assets/2817002/19786092/215d2dc2-9c94-11e6-910d-191b7b74f4d2.png">

### API

The `CliMenu` object is constructed via the Builder class

```php
$menu = (new CliMenuBuilder)
    /**
     *  Customise
    **/
    ->build();
```

Once you have a menu object, you can open and close it like so:

```php
$menu->open();
$menu->close();
```

#### Appearance

You can change the foreground and background colour of the menu to any of the following colours:

* black
* red
* green
* yellow
* blue
* magenta
* cyan
* white

```php
$menu = (new CliMenuBuilder)
    ->setForegroundColour('green')
    ->setBackgroundColour('black')
    ->build();
```

The width, padding and margin can also be customised:

```php
$menu = (new CliMenuBuilder)
    ->setWidth(200)
    ->setPadding(10)
    ->setMargin(5)
    ->build();
```

Modify the exit button text:

```php
$menu = (new CliMenuBuilder)
    ->setExitButtonText("Don't you want me baby?")
    ->build();
```

You can remove the exit button altogether:

```php
$menu = (new CliMenuBuilder)
    ->disableDefaultItems()
    ->build();
```

Note: This will also disable the Go Back button for sub menus.

The marker displayed by the side of the currently active item can be modified, UTF-8 characters are supported.
The marker for un-selected items can also be modified. If you want to disable it, just set it to a space character.

```php
$menu = (new CliMenuBuilder)
    ->setUnselectedMarker('❅')
    ->setSelectedMarker('✏')
    
    //disable unselected marker
    ->setUnselectedMarker(' ')
    ->build();
```

You can give your menu a title and you can customise the separator, a line which displays under the title.
Whatever string you pass to `setTitleSeparator` will be repeated for the width of the Menu.

```php
$menu = (new CliMenuBuilder)
    ->setTitle('One Menu to rule them all!')
    ->setTitleSeparator('*-')
    ->build();
```

#### Item Extra

You can optionally display some arbitrary text on the right hand side of an item. You can customise this text and
you indicate which items to display it on. We use it to display `[COMPLETED]` on completed exercises, where the menu lists
exercises for a workshop application. 

The fourth parameter to `addItem` is a boolean whether to show the item extra or not. It defaults to false.

```php
$menu = (new CliMenuBuilder)
    ->setItemExtra('✔')
    ->addItem('Exercise 1', function (CliMenu $menu) { echo 'I am complete!'; }, [] , true)
    ->build();
```

#### Items

There a few different types of items you can add to your menu

* Selectable Item - This is the type of item you need for something to be selectable (you can hit enter and it will call your invokable) 
* Line Break Item - This is used to break up areas, it can span multiple lines and will be the width of Menu. Whatever string is passed will be repeated.
* Static Item - This will print whatever text is passed, useful for headings.
* Ascii Art Item - Special item which allows usage of Ascii art. It takes care of padding and alignment.
* Sub Menu Item - Special item to allow an item to open another menu. Useful for an options menu.

#### Selectable Item

```php
$menu = (new CliMenuBuilder)
    ->addItem('The Item Text', function (CliMenu $menu) { 
        echo 'I am alive!'; 
    })
    ->build();
```

You can add multiple items at once like so:

```php
$callable = function (CliMenu $menu) {
    echo 'I am alive!';
};

$menu = (new CliMenuBuilder)
    ->addItems([
        ['Item 1', $callable],
        ['Item 2', $callable],
        ['Item 3', $callable],
    ])
    ->build();
```

Note: You can add as many items as you want and they can all have a different action. The action is the separate parameter
and must be a valid PHP `callable`. Try using an `Invokable` class to keep your actions easily testable.

#### Line Break Item

```php
$menu = (new CliMenuBuilder)
    ->addLineBreak('<3', 2)
    ->build();
```

The above would repeat the character sequence `<3` across the Menu for 2 lines

#### Static Item

Static items are similar to Line Breaks, however, they don't repeat and fill. It is output as is.
If the text is longer than the width of the Menu, it will be continued on the next line.

```php
$menu = (new CliMenuBuilder)
    ->addStaticItem('AREA 1')
    //add some items here
    ->addStaticItem('AREA 2')
    //add some boring items here
    ->addStaticItem('AREA 51')
    //add some top secret items here 
    ->build();
```

#### Ascii Art Item

The following will place the Ascii art in the centre of your menu. Use these constants to alter the 
alignment:

* AsciiArtItem::POSITION_CENTER
* AsciiArtItem::POSITION_LEFT
* AsciiArtItem::POSITION_RIGHT

```php

$art = <<<ART
        _ __ _
       / |..| \
       \/ || \/
        |_''_|
      PHP SCHOOL
LEARNING FOR ELEPHANTS
ART;

$menu = (new CliMenuBuilder)
    ->addAsciiArt($art, AsciiArtItem::POSITION_CENTER)
    ->build();
```    

#### Sub Menu Item

Sub Menus are really powerful! You can add Menus to Menus, whattttt?? You can have your main menu and then an options menu.
The options item will look like a normal item except when you hit it, you will enter to another menu, which
can have different styles and colours!

```php

$callable = function (CliMenu $menu) {
    echo "I'm just a boring selectable item";
};

$menu = (new CliMenuBuilder)
    ->addItem('Normal Item', $callable)
    ->addSubMenu('Super Sub Menu')
        ->setTitle('Behold the awesomeness')
        ->addItem(/** **/)
        ->end()
    ->build();
```

In this example a single sub menu will be created. Upon entering the sub menu, you will be able to return to the main menu
or exit completely. A Go Back button will be automatically added, you can customise this text like so:

```php
->addSubMenu('Super Sub Menu')
    ->setTitle('Behold the awesomeness')
    ->setGoBackButtonText('Descend to chaos')
```    

There are a few things to note about the syntax and builder process here

1. `addSubMenu` returns an instance of `CliMenuBuilder` so you can can customise exactly the same way you would the parent.
2. If you do not modify the styles of the sub menu (eg, colours) it will inherit styles from the parent!
3. You can call `end()` on the sub menu `CliMenuBuilder` instance to get the parent `CliMenuBuilder` back again. This is useful for chaining.

If you need the `CliMenu` instance of the Sub Menu you can grab it after the main menu has been built.

```php
$mainMenuBuilder = new CliMenuBuilder;
$subMenuBuilder = $mainMenuBuilder->addSubMenu('Super Sub Menu');

$menu = $mainMenuBuilder->build();
$subMenu = $mainMenuBuilder->getSubMenu('Super Sub Menu');
```

You can only do this after the main menu has been built, this is because the main menu builder takes care of building all sub menus.

#### Disabling Items & Sub Menus

In this example we are disabling certain items and a submenu but still having them shown in the output. 

```php
$itemCallable = function (CliMenu $menu) {
    echo $menu->getSelectedItem()->getText();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu Disabled Items')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable, [], false, true)
    ->addItem('Third Item', $itemCallable, [], false, true)
    ->addSubMenu('Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Submenu')
        ->addItem('You can go in here!', $itemCallable)
        ->end()
    ->addSubMenu('Disabled Submenu')
        ->setTitle('Basic CLI Menu Disabled Items > Disabled Submenu')
        ->addItem('Nope can\'t see this!', $itemCallable)
        ->disableMenu()
        ->end()
    ->addLineBreak('-')
    ->build();
```

The forth param on the `->addItem` call is what disables an item while the `->disableMenu()` call disables the relevent menu. 

The outcome is a full menu with dimmed rows to denote them being disabled. When a user navigates these items are jumped over to the next available selectable item.

#### Redrawing the menu

You can modify the menu and its style when executing an action and then you can redraw it! In this example we will toggle the background
colour in an action.

```php
$itemCallable = function (CliMenu $menu) {
    $menu->getStyle()->setBg($menu->getStyle()->getBg() === 'red' ? 'blue' : 'red');
    $menu->redraw();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

#### Getting, Removing and Adding items

You can also interact with the menu items in an action:

```php
use PhpSchool\CliMenu\MenuItem\LineBreakItem;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    foreach ($menu->getItems() as $item) {
        $menu->removeItem($item);
    }
    
    $menu->addItem(new LineBreakItem('-'));

    $menu->redraw();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

#### Dialogues

##### Flash

Show a one line message over the top of the menu. It has a separate style object which is colored by default different
to the menu. It can be modified to suit your own style. The dialogue is dismissed with any key press. In the example
below we change the background color on the flash to green.

```php
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');
    
$itemCallable = function (CliMenu $menu) {
    $flash = $menu->flash("PHP School FTW!!");
    $flash->getStyle()->setBg('green');
    $flash->display();
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

##### Confirm

Prompts are very similar to flashes except that a button is shown which has to be selected to dismiss them. The button
text can be customised.

```php
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\CliMenuBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');

$itemCallable = function (CliMenu $menu) {
    $menu->confirm('PHP School FTW!')
        ->display('OK!');
};

$menu = (new CliMenuBuilder)
    ->setTitle('Basic CLI Menu')
    ->addItem('First Item', $itemCallable)
    ->addItem('Second Item', $itemCallable)
    ->addItem('Third Item', $itemCallable)
    ->addLineBreak('-')
    ->build();

$menu->open();
```

---

Once you get going you might just end up with something that looks a little like this... 

![Learn You PHP CLI Menu](https://cloud.githubusercontent.com/assets/2174476/11409864/be082444-93ba-11e5-84ab-1b6cfa38aef8.png)

You can see the construction code here for more clarity on how to perform advanced configuration:
[PHP School](https://github.com/php-school/php-workshop/blob/3240d3217bbf62b1063613fc13eb5adff2299bbe/src/Factory/MenuFactory.php)

### Docs Translations 
_(This might not be kept up-to-date since it's a community translation)_
See this doc in [Brazilian Portuguese (pt_BR)](docs/pt_BR/README.md) 


### Integrations

 * [Symfony Console](https://github.com/RedAntNL/console)
 * [Laravel](https://github.com/nunomaduro/laravel-console-menu)
