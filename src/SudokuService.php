<?php

namespace Secture\Sudoku;

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
    private function completeCell(array &$board, int $rowPosition, int $columnPosition): bool
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

        if (count($possibilitiesValues) == 1) {
            // If there is only one valid possibility, fill it in
            $board[$rowPosition][$columnPosition] = $possibilitiesValues[0];
            return true;
        } else {
            $board[$rowPosition][$columnPosition] = $possibilitiesValues;
            return false;
        }
    }

    /* Given an array of used values in row/column/square check if the is correct complete */
    private function checkSection(array $usedValues): bool
    {
        $expected = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        sort($usedValues);

        return ($expected === $usedValues) ? true : false;
    }

    /* Recibe board by reference and check if the cell only have one possible value */
    private function fillCellWithOnePossibleValue(&$board, $possibilities, $segment, $rowPosition, $columnPosition): bool
    {
        $updated = false;
        for ($i = 0; $i < count($possibilities); $i++) {
            $possibility = $possibilities[$i];
            $counter = 0;
            foreach ($segment as $cell) {
                if (is_array($cell)) {
                    if (in_array($possibility, $cell)) {
                        $counter++;
                    }
                } else {
                    if ($cell == $possibility) {
                        $counter++;
                    }
                }
            }
            if ($counter == 1) {
                $board[$rowPosition][$columnPosition] = $possibility;
                $updated = true;
                break;
            }
        }

        return $updated;
    }

    /* Recive board by reference, try to complete and check if one exits one possible value */
    function oneValueCellConstraint(&$board): bool
    {
        $updated = false;

        // Convert every cell into an array of $possibilities
        for ($rowPosition = 0; $rowPosition < 9; $r++) {
            for ($columnPosition = 0; $columnPosition < 9; $c++) {
                if ($board[$r][$c] == 0) {
                    $updated = $this->completeCell($board, $r, $c) || $updated;
                }
            }
        }

        // Check for any possibility appear once-only in the row, column, or quadrant. If it does, fill it in.
        for ($rowPosition = 0; $rowPosition < 9; $r++) {
            for ($columnPosition = 0; $columnPosition < 9; $c++) {
                if (is_array($board[$r][$c])) {
                    $possibilities = $board[$r][$c];
                    $updated = $this->fillCellWithOnePossibleValue($board, $possibilities, $this->getRow($board, $r), $r, $c) ||
                        $this->fillCellWithOnePossibleValue($board, $possibilities, $this->getColumn($board, $c), $r, $c) ||
                        $this->fillCellWithOnePossibleValue($board, $possibilities, $this->getSquare($board, $this->squareCoordinates[$r][$c]), $r, $c) || $updated;
                }
            }
        }

        // Reinitialize cells back to zero before ending
        for ($rowPosition = 0; $rowPosition < 9; $rowPosition++) {
            for ($columnPosition = 0; $columnPosition < 9; $columnPosition++) {
                if (is_array($board[$r][$c])) {
                    $board[$r][$c] = 0;
                }
            }
        }

        return $updated;
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
                    $this->completeCell($board, $rowPosition, $columnPosition);
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

    public function printBoard(array $board): string
    {
        $template = "<table style='border-collapse:collapse;border-spacing:0;border:3px solid #000;'>";

        for ($i = 0; $i < 9; $i++) {
            if ($i == 2 || $i == 5) {
                $tableStyleSeparator = 'style="border-bottom:3px solid #000;"';
            } else {
                $tableStyleSeparator = '';
            }
            $template .= "<tr $tableStyleSeparator>";
            for ($j = 0; $j < 9; $j++) {
                if ($j == 2 || $j == 5) {
                    $tableRightSeparator = 'border-right:3px solid #000;';
                } else {
                    $tableRightSeparator = '';
                }
                $template .= "<td style='width:40px;height:40px;text-align:center;border:1px solid #000;font-size: 30px;$tableRightSeparator'>" . $board[$i][$j] . "</td>";
            }
            $template .= "</tr>";
        }

        $template .= "</table>";

        return $template;
    }

    public function resolve(array $board): bool|array
    {
        $updated = true;
        $solved = false;

        /* First try to resolve the non-complex cells */
        while ($updated && !$solved) {
            $updated = $this->oneValueCellConstraint($board);
            //Check if update
            $solved = $this->isSolved($board);
        }

        // Brute force to finish off.  
        if (!$solved) {
            $board = $this->backtrackBased($board);
            if ($board == false) return false;
            $this->isSolved($board);
        }

        return $board;
    }
}
