<?php

namespace Secture\Service;

class SudokuService
{
    public function getRow($board, $rowPosition)
    {
        return $board[$rowPosition];
    }

    }
}
