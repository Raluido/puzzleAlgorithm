<?php

function getPuzzleAssemble($w, $h, $puzzlePieces)
{
    $puzzleComplete = array();
    $leftSide = null;
    $topSide = null;
    $rightSide = null;
    $bottomSide = null;

    for ($i = 0; $i < $w; $i++) {
        for ($j = 0; $j < $h; $h++) {
            $puzzleComplete[$i][$j] = findAPiece($i, $j, $w, $h, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePieces);
            $leftSide = ($puzzleComplete[$i][$j])[2];
            $topSide = ($puzzleComplete[$i][$j])[3];
        }
    }
    return $puzzleComplete;
}


function findAPiece($x, $y, $w, $h, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePieces)
{
    if ($x == 0 && $y == 0) {
        return rotatePuzzle($puzzlePieces, 0, 0, $rightSide, $bottomSide);
    } else if ($x == $w && $y == 0) {
        return rotatePuzzle($puzzlePieces, $leftSide, 0, 0, $bottomSide);
    } else if ($x == 0 && $y == $h) {
        return rotatePuzzle($puzzlePieces, 0, $topSide, $rightSide, 0);
    } else if ($x == $w && $y == $h) {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, 0, 0);
    } else if (($x == 0 && $y < $h && $y > 0 || $x == $w && $y > 0  && $y < $h || $y == 0 && $x > 0 && $x < $w || $y == $h && $x > 0 && $x < $w)) {
        return rotatePuzzle($puzzlePieces, ($x == 0) ? $leftSide = 0 : $leftSide, ($x == $w) ? $rightSide = 0 : $rightSide, ($y == 0) ? $topSide = 0 : $topSide, ($y == $h) ? $bottomSide = 0 : $bottomSide);
    } else {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, $rightSide, $bottomSide);
    }
}

function getCount($a, $b)
{
    if ($a == $b) return 0;
    return ($a < $b) ? -1 : 1;
}


function rotatePuzzle($puzzle, $leftSide = null, $topSide = null, $rightSide = null, $bottomSide = null)
{
    $arrTemp = [$leftSide, $topSide, $rightSide, $bottomSide];
    $getIt = false;

    foreach ($puzzle as $key => $index) {
        for ($j = 0; $j < count($index) - 1; $j++) {
            $temp = $index[$j];
            $index[$j] = $index[$j + 1];
            $index[$j + 1] = $temp;
        }
        for ($i = 0; $i < count($arrTemp); $i++) {
            if ($arrTemp[$i] != null && $arrTemp[$i] == $index[$i]) {
                $getIt = true;
            } else {
                $getIt = false;
            }
        }
        if ($getIt) {
            return $index;
        }
    }
}


$fOpen = fopen('4x4.txt', 'a+');

while ($fGets = (fgets($fOpen))) {
    $puzzlePieces[] = explode(' ', $fGets);
}

[$w, $h] = $puzzlePieces[0];
array_shift($puzzlePieces);

$resultTitle = "\n\nSoluciones\n______________";

$puzzleAssembled = getPuzzleAssemble($w, $h, $puzzlePieces);

foreach ($puzzleAssembled as $key => $index) {
    $result .= $index . '\n';
}

fwrite($fOpen, $resultTitle);
fwrite($fOpen, $result);
fclose($fOpen);
