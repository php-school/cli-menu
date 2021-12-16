<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu\MenuItem;

use PhpSchool\CliMenu\MenuStyle;
use PhpSchool\CliMenu\Style\DefaultStyle;
use PhpSchool\CliMenu\Style\ItemStyle;
use PhpSchool\CliMenu\Util\StringUtil;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 */
class StaticItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var DefaultStyle
     */
    private $style;

    public function __construct(string $text)
    {
        $this->text = $text;

        $this->style = new DefaultStyle();
    }

    /**
     * The output text for the item
     */
    public function getRows(MenuStyle $style, bool $selected = false) : array
    {
        return explode("\n", StringUtil::wordwrap($this->text, $style->getContentWidth()));
    }

    /**
     * Return the raw string of text
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Set the raw string of text
     */
    public function setText(string $text) : void
    {
        $this->text = $text;
    }

    /**
     * Execute the items callable if required
     */
    public function getSelectAction() : ?callable
    {
        return null;
    }

    /**
     * Can the item be selected
     */
    public function canSelect() : bool
    {
        return false;
    }

    /**
     * Whether or not we are showing item extra
     */
    public function showsItemExtra() : bool
    {
        return false;
    }

    /**
     * Enable showing item extra
     */
    public function showItemExtra() : void
    {
        //noop
    }

    /**
     * Disable showing item extra
     */
    public function hideItemExtra() : void
    {
        //noop
    }

    /**
     * @return DefaultStyle
     */
    public function getStyle() : ItemStyle
    {
        return $this->style;
    }

    public function setStyle(DefaultStyle $style) : void
    {
        $this->style = $style;
    }
}
