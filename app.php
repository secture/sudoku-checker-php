<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Secture\Sudoku\SudokuChecker;
use Secture\Sudoku\SudokuGenerator;

/* Here we generate a sudoku. You can set to 9 level difficulty.*/
$sudokuGeneratorInstance = new sudokuGenerator(1);
$sudoku  = $sudokuGeneratorInstance->puzzle();

/* Here we resolve the sudoku if have solution */
$checker = new SudokuChecker();
$checker->check($sudoku);
