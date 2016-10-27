<?php

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

    /**
     * @param int $count
     */
    public function newLine($count = 1)
    {
        foreach (range(1, $count) as $i) {
            $this->rows[] = "\n";
        }
    }

    /**
     * @param array $rows
     */
    public function addRows(array $rows = [])
    {
        foreach ($rows as $row) {
            $this->rows[] = $row;
        }
    }

    /**
     * @param string $row
     */
    public function addRow($row)
    {
        $this->rows[] = $row;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->rows);
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }
}
