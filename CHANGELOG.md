# Change Log
All notable changes to this project will be documented in this file.
Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [Unreleased][unreleased]
### Added

### Changed

### Fixed

### Removed

## [4.3.0]
### Fixed
 - PHP 8.1 Support (#252, #249)

### Added
 - declare(strict_types=1) everywhere

## [4.2.0]
### Added
 - Yes/no confirmation dialogue (#248)
 - Ability to add multiple checkbox and radio items (#241)

## [4.1.0]
### Added
 - Ability to modify password length for password input (#235)
 - Improve the formatting of disabled menu items in different terminals (#236)
 - Support for PHP8 (#240)

## [4.0.0]
### Added
 - Add PHP 7.4 support (#183)
 - CheckboxItem & RadioItem (#186, #189, #193, #194, #226)
 - Ability to force display extra (#187)
 - Individual style objects for each item type (#211, #212, #213, #214, #216, #230)
 - Method getStyle() to interface PhpSchool\CliMenu\MenuItem\MenuItemInterface
 
### Fixed
 - Fixed item extra rendering outside of menu (#66, £184, #187)
 - Fix unresponsive menu upon closing and reopening (#198)
 - Menu styles incorrectly propagating to submenus (#201, #210)
 - Various issues with the menu width, when the terminal was too small (#223, #220, #219)

### Removed
 - Remove rebinding $this in builder closures so we can access the real $this (#191, #192, #196)
 - Marker methods from PhpSchool\CliMenu\MenuStyle: 
            #getSelectedMarker()
            #setSelectedMarker()
            #getUnselectedMarker()
            #setUnselectedMarker()
            #getMarker()
 - PhpSchool\CliMenu\MenuItem\SelectableTrait
 - Marker methods from PhpSchool\CliMenu\Builder\CliMenuBuilder:
            #setUnselectedMarker()
            #setSelectedMarker()

## [3.2.0]
### Added
 - Allow ESC key to "cancel" editing an input (#174)
 - Methods for disabling the default VIM mappings and setting your own (#172)
 - Ability to set custom validator on Text and Number inputs (#177)
 - Ability to turn on automatic item shortcuts (#176)

## [3.1.0]
### Changed
 - Update dependencies + fix some static analysis issues

## [3.0.0]
### Changed
 - Optimise redrawing to reduce flickering (#83)
 - Use parent menu terminal when creating sub menus to reduce object graph (#94)
 - Do not print right margin. Causes menu to wrap even when row fits in terminal (#116)
 - CliMenu throws a \RuntimeException if it is opened with no items added (#146, #130)
 - Sub Menus are configured via closures (#155)
 - Remove restriction of 1 character length for markers (#141)
 - Remove the mandatory space after markers for now they can be of any length (#154)
 
### Added
 - Added type hints everywhere (#79)
 - Added phpstan to the travis build (#79)
 - Input dialogue system for prompting users. Comes with text, number and password inputs (#81)
 - Added ability to pass already prepared CliMenuBuilder instance to CliMenuBuilder#addSubMenuFromBuilder (#85, 155)
 - Added CliMenu#addItems & CliMenu#setItems to add multiple items and replace them (#86)
 - Added custom control mapping - link any key to a callable to immediately execute it (#87)
 - Added MenuMenuItem#getSubMenu (#92)
 - Added alternate text to AsciiArtItem to display if the ascii art is too large for the current terminal (#93)
 - Added the ability to pass existing MenuStyle instance to dialogues and inputs for consistent themes and reduced object graph (#99)
 - Added CSS like borders (#100)
 - Added option to auto center menu with CliMenuBuilder#setMarginAuto (#103)
 - Added option to auto center menu with CliMenuBuilder#setMarginAuto (#103)
 - Added support for 256 colours with automatic and manual fallback to 8 colours (#104)
 - Added clear option to CliMenu#redraw useful for when reducing the terminal width (#117)
 - Added ability to set top/bottom and left/right padding independently (#121)
 - Added a new Split Item item type which allows displaying multiple items on one line (#127)
 - Added setText methods to various items so they can be modified at runtime (#153)
 - Added MenuStyle#hasChangedFromDefaults to check if a MenuStyle has been modified (#149)
 - Added CliMenu#setTitle and CliMenu#setStyle (#155)
 - Added CliMenuBuilder#getStyle to get the current style object for the menu
 
### Fixed
 - Fixed sub menu go back button freezing menu (#88)
 - Fixed centering ascii art items with trailing white space (#102)
 - Enable cursor when exiting menu (#110)
 - Fixed (#71) - changed padding calculation when row too long to stop php notices (#112)
 - Fixed wordwrap helper (#134)
 - Fixed selected item issues when adding/setting/removing items (#156)
 - Fix infinite loop when no selectable items in menu (#159, #144)
 
### Removed
 - Dropped PHP 5.x and PHP 7.0 support (#79)
 - Removed the Terminal namespace which has been migrated to php-school/terminal (#81)
 - Removed MenuStyle::getDefaultStyleValues (#149)
 - Removed CliMenuBuilder#setTerminal (#149)
 - Removed CliMenuBuilder#getSubMenu (#155)
 - Removed CliMenuBuilder#getMenuStyle (#155)
 - Removed CliMenuBuilder#end (#155)

## [2.1.0]
### Changed
 - Use new static for submenu to allow subclassing (#68)
 
### Added
 - Add emacs style up/down shortcuts ctrl+n and ctrl+p (#67)

## [2.0.2]
### Fixed
 - Don't output ascii art if the terminal width is too small (#63)

## [2.0.1]
### Fixed
 - Reset array keys after removing an item from the menu (#61)

## [2.0.0]
### Fixed
 - PHPUnit deprecations - updated to createMock()
   
### Changed
 - Require ext-posix (#50)
 - Make MenuStyle easier to construct by only allowing changes to be made via setters (#45)
 
### Added
 - Added getStyle() to CliMenu to get access to the style object from the menu itself (#42)
 - Added redraw method to CliMenu which can redraw the menu immediately with any style changes. See 
   examples/crazy-redraw.php for an example (#43)
 - Added tests for child menu style inheritance (#44)
 - Add getter getItems() to get all items from the menu (#46)
 - Add method removeItem(ItemInterface $item) to remove an item from the menu (#46)
 - Ability to toggle item extra while the menu is open - see examples/toggle-item-extra.php (#46)
 - Added dialogues flash and confirm - they both display some text on top of the menu, flash is dismissed with 
   any key press where the confirm requires enter to be pressed on the provided button. 
   See examples/confirm.php and examples/flash.php (#49)
 
### Removed
 - Removed windows terminal - many required terminal features are unavailable (#50)
 - Individual component instantiation restrictions (#41)
 
 ## [1.2.0] 
 ### Added
  - Added ability to disable menu items and sub-menus, they will appear dimmed and will be un-selectable (#40)
