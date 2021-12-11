<?php

namespace Secture\Service;

class SudokuService
{
    /* Define the diferents square positions */
    private $squareCoordinates = [
        [1, 1, 1, 2, 2, 2, 3, 3, 3],
        [1, 1, 1, 2, 2, 2, 3, 3, 3],
        [1, 1, 1, 2, 2, 2, 3, 3, 3],
        [4, 4, 4, 5, 5, 5, 6, 6, 6],
        [4, 4, 4, 5, 5, 5, 6, 6, 6],
        [4, 4, 4, 5, 5, 5, 6, 6, 6],
        [7, 7, 7, 8, 8, 8, 9, 9, 9],
        [7, 7, 7, 8, 8, 8, 9, 9, 9],
        [7, 7, 7, 8, 8, 8, 9, 9, 9]
    ];

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

    /* Given a board and square position, we iterate the row and colums to return the cells of the square */
    public function getSquare($board, $squarePosition)
    {
        $cellsOfSquare = [];
        for ($row = 0; $row < 9; $row++) {
            for ($column = 0; $column < 9; $column++) {
                if ($squarePosition == $this->squareCoordinates[$row][$column]) {
                    array_push($cellsOfSquare, $board[$row][$column]);
                }
            }
        }

        return $cellsOfSquare;
    }
}
