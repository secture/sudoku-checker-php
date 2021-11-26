<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Secture\Sudoku\SudokuChecker;

$checker = new SudokuChecker();

$checker->check();
