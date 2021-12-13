<?php

declare(strict_types=1);

namespace Secture\Sudoku;


use Secture\Sudoku\SudokuService;

final class SudokuChecker
{
    public function check($board)
    {
        $sudokuServiceInstance = new SudokuService();

        $sudokuOriginalBoard = $sudokuServiceInstance->printBoard($board);
        $sudokuResolved = $sudokuServiceInstance->resolve($board);

        echo $sudokuOriginalBoard;
        echo "<br>";

        if ($sudokuResolved == false) {
            echo 'Este Sudoku no tiene solucion';
            return false;
        }
        
        echo $sudokuServiceInstance->printBoard($sudokuResolved);
    }
}
