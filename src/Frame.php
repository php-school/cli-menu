<?php
declare(strict_types=1);

namespace PhpSchool\CliMenu;

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

    public function addRows(array $rows = []) : void
    {
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
