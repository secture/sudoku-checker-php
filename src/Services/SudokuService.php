<?php

namespace Secture\Service;

final class SudokuService
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

    private function getRow(array $board, int $rowPosition): array
    {
        return $board[$rowPosition];
    }

    /* Given a board and column position, we iterate the rows to return a column */
    private function getColumn(array $board, int $columnPosition): array
    {

        $columnCells = [];
        for ($row = 0; $row < 9; $row++) {
            array_push($columnCells, $board[$row][$columnPosition]);
        }

        return $columnCells;
    }

    /* Given a board and square position, we iterate the row and colums to return the cells of the square */
    private function getSquare(array $board, int $squarePosition): array
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
    private function completeCell(array $board, int $rowPosition, int $columnPosition): array
    {
        /* Retrive all the values used on the row, column and square in base one cell */
        $usedValues = [...$this->getRow($board, $rowPosition), ...$this->getColumn($board, $columnPosition), ...$this->getSquare($board, $this->squareCoordinates[$rowPosition][$columnPosition])];

        /* Create an array with the possible values for the cell*/
        $possibilitiesValues = [];
        for ($thePosibilityValue = 1; $thePosibilityValue <= 9; $thePosibilityValue++) {
            if (!in_array($thePosibilityValue, $usedValues)) {
                array_push($possibilitiesValues, $thePosibilityValue);
            }
        }

        /*  If there is only one valid possibility, fill it in. Else fill it with all the possible values for the cell */
        $board[$rowPosition][$columnPosition] = (count($possibilitiesValues) == 1) ? $possibilitiesValues[0] : $possibilitiesValues;
        
        return $board;
    }

    /* Given an array of used values in row/column/square check if the is correct complete */
    private function checkSection(array $usedValues): bool
    {
        $expected = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        sort($usedValues);

        return ($expected === $usedValues) ? true : false;
    }

    private function isSolved(array $board): bool
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

    /* Given a board try the resolve by brute-force  */
    private function backtrackBased(array $board): array|bool
    {
        for ($rowPosition = 0; $rowPosition < 9; $rowPosition++) {
            for ($columnPosition = 0; $columnPosition < 9; $columnPosition++) {
                // Process each incomplete cell
                if ($board[$rowPosition][$columnPosition] == 0) {

                    //Fill imcomplete cell with the correct value or possible values
                    $board  = $this->completeCell($board, $rowPosition, $columnPosition);
                    //Verify if is solved
                    if ($this->isSolved($board)) return $board;

                    // If exist a list of possibilities, iterate them and recurse
                    $cell = $board[$rowPosition][$columnPosition];
                    if (is_array($cell)) {
                        for ($i = 0; $i < count($cell); $i++) {
                            // Create a temporary board for each recursion. 
                            $boardCopy = $board;
                            // Choose a value
                            $boardCopy[$rowPosition][$columnPosition] = $cell[$i];
                            // Recurse again using new board
                            if ($completedBoard = $this->backtrackBased($boardCopy)) {
                                return $completedBoard;
                            }
                        }

                        //Can't resolve, dead end.
                        return false;
                    }
                }
            }
        }

        return false;
    }

    private function printCell(mixed $value)
    {
        //If cell is not resolve return a dot.
        if (is_array($value) | $value == 0) return ".";

        return $value;
    }

    public function printBoard(array $board): string
    {
        $template = "";
        for ($i = 0; $i < 9; $i++) {
            $rowCells = $this->getRow($board, $i);
            if ($i % 3 == 0) $template .= "|=======|=======|=======|<br>";
            $template .= "|" .
                $this->printCell($rowCells[0]) . $this->printCell($rowCells[1]) .  $this->printCell($rowCells[2]) .  "|" .
                $this->printCell($rowCells[3]) . $this->printCell($rowCells[4]) .  $this->printCell($rowCells[5]) .  "|" .
                $this->printCell($rowCells[6]) . $this->printCell($rowCells[7]) .  $this->printCell($rowCells[8]) . "|<br>";
        }

        $template .= "|=======|=======|=======|<br>";

        return $template;
    }

    public function resolve(array $board): array
    {
        $board = $this->backtrackBased($board);
        $this->isSolved($board);

        return $board;
    }
}
