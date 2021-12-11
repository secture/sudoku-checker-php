<?php

namespace Secture\Service;

class SudokuService
{
    /* Define the diferents square positions */
    private array $squareCoordinates = [
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

    private function getRow(array $board, int $rowPosition)
    {
        return $board[$rowPosition];
    }

    /* Given a board and column position, we iterate the rows to return a column */
    private function getColumn(array $board, int $columnPosition)
    {

        $columnCells = [];
        for ($row = 0; $row < 9; $row++) {
            array_push($columnCells, $board[$row][$columnPosition]);
        }

        return $columnCells;
    }

    /* Given a board and square position, we iterate the row and colums to return the cells of the square */
    private function getSquare(array $board, int $squarePosition)
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

    /* Fill the cell with the possiblities values or the correct value */
    private function completeCell(array $board, int $rowPosition, int $columnPosition)
    {
        /* Retrive all the values used on the row, column and square in base one cell */
        $usedValues = [$this->getRow($board, $rowPosition), $this->getColumn($board, $columnPosition), $this->getSquare($board, $this->square_coordinates[$rowPosition][$columnPosition])];

        /* Create an array with the possible values for the cell*/
        $possibilitiesValues = [];
        for ($thePosibilityValue = 1; $thePosibilityValue <= 9; $thePosibilityValue++) {
            if (!in_array($thePosibilityValue, $usedValues)) {
                array_push($possibilitiesValues, $thePosibilityValue);
            }
        }

        if (count($possibilitiesValues) == 1) {
            /*  If there is only one valid possibility, fill it in */
            $board[$rowPosition][$columnPosition] = $possibilitiesValues[0];

            return true;
        } else {
            /* Else fill it with all the possible values for the cell*/
            $board[$rowPosition][$columnPosition] = $possibilitiesValues;

            return false;
        }
    }

    /* Given an array of used values in row/column/square check if the is correct complete */
    private function checkSection(array $usedValues)
    {
        $expected = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        sort($usedValues);

        return ($expected === $usedValues) ? 'true' : 'false';
    }

    private function isSolved(array $board)
    {
        $valid = true;

        // Check all rows
        for ($rowPosition = 0; $rowPosition < 9 && $valid == true; $rowPosition++) {
            $valid = $this->checkSection($this->getRow($board, $rowPosition)) ? true : false;
        }

        // Check all columns
        for ($columnPosition = 0; $columnPosition < 9 && $valid == true; $columnPosition++) {
            $valid = $this->checkSection($this->getColumn($board, $columnPosition)) ? true : false;
        }

        // Check all square
        for ($squarePosition = 1; $squarePosition < 9 && $valid == true; $squarePosition++) {
            $valid = $this->checkSection($this->getSquare($board, $squarePosition)) ? true : false;
        }

        return $valid;
    }
}
