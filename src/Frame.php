<?php

namespace PhpSchool\CliMenu;

use PhpSchool\CliMenu\MenuItem\MenuItemInterface;

/**
 * Represents the current screen being displayed
 * contains all rows of output
 *
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Frame implements \Countable
{
    /**
     * @var array
     */
    private $rows = [];

    public function newLine(int $count = 1) : void
    {
        foreach (range(1, $count) as $i) {
            $this->rows[] = "\n";
        }
    }

    public function addRows(array $params = []) : void
    {
        if (count($params) == 2 && is_array($params[0]) && $params[1] instanceof MenuItemInterface) {
            list($rows, $item) = $params;
            $item->setStartRowNumber(count($this->rows));
        } else {
            $rows = $params;
        }

        foreach ($rows as $row) {
            $this->rows[] = $row;
        }
    }

    public function addRow(string $row) : void
    {
        $this->rows[] = $row;
    }

    public function count() : int
    {
        return count($this->rows);
    }

    public function getRows() : array
    {
        return $this->rows;
    }
}
