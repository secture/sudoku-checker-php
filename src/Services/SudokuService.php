<?php

namespace Secture\Service;

class SudokuService
{
    public function getRow($board, $rowPosition)
    {
        return $board[$rowPosition];
    }

    /* Given a board and column position, we iterate the rows to return a column */
    public function getColumn($board, $columnPosition)
    {

        $columnCells = [];
        for ($row = 0; $row < 9; $row++) {
            array_push($columnCells, $board[$row][$columnPosition]);
        }

        return $columnCells;
    }
    }
}
