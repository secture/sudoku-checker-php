<?php

declare(strict_types=1);

namespace Secture\Sudoku;


use Secture\Sudoku\SudokuResolver;

final class SudokuChecker
{
    public function check($board)
    {
        $SudokuResolverInstance = new SudokuResolver();

        $sudokuOriginalBoard = $SudokuResolverInstance->printBoard($board);
        $sudokuResolved = $SudokuResolverInstance->resolve($board);

        echo $sudokuOriginalBoard;
        echo "<br>";

        if ($sudokuResolved == false) {
            echo 'Este Sudoku no tiene solucion';
            return false;
        }
        
        echo $SudokuResolverInstance->printBoard($sudokuResolved);
    }
}
