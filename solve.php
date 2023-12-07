<?php

function getPuzzleAssemble($w, $h, $puzzlePieces)
{
    $puzzleComplete = array();
    $leftSide = null;
    $topSide = null;
    $rightSide = null;
    $bottomSide = null;

    for ($i = 0; $i < 1; $i++) {
        for ($j = 0; $j < 2; $j++) {
            $puzzleComplete[$i][] = findAPiece($i, $j, $w, $h, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePieces);
            $leftSide = $puzzleComplete[$i][$j][2];
            $topSide = $puzzleComplete[$i][$j][3];
        }
    }
    return $puzzleComplete;
}


function findAPiece($x, $y, $w, $h, $leftSide = null, $topSide = null, $rightSide = null, $bottomSide = null, $puzzlePieces = null)
{
    if ($x == 0 && $y == 0) {
        // print_r("find a piece");
        return rotatePuzzle($puzzlePieces, 0, 0, $rightSide, $bottomSide);
    } else if ($y == $w && $x == 0) {
        return rotatePuzzle($puzzlePieces, $leftSide, 0, 0, $bottomSide);
    } else if ($y == 0 && $x == $h) {
        return rotatePuzzle($puzzlePieces, 0, $topSide, $rightSide, 0);
    } else if ($y == $w && $x == $h) {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, 0, 0);
    } else if (($x == 0 && $y < $w && $y > 0 || $y == $w && $x > 0  && $x < $h || $y == 0 && $x > 0 && $x < $h || $x == $h && $y > 0 && $y < $w)) {
        print_r($leftSide);
        print_r($topSide);
        return rotatePuzzle($puzzlePieces, ($y == 0) ? $leftSide = 0 : $leftSide, ($x == 0) ? $topSide = 0 : $topSide, ($y == $w) ? $rightSide = 0 : $rightSide, ($x == $h) ? $bottomSide = 0 : $bottomSide);
    } else {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, $rightSide, $bottomSide);
    }
}

function rotatePuzzle($puzzle, $leftSide = null, $topSide = null, $rightSide = null, $bottomSide = null)
{
    $arrTemp = [$leftSide, $topSide, $rightSide, $bottomSide];

    foreach ($puzzle as $key => $index) {
        if(in_array($key,    )){
            break;
        }
        for ($j = 0; $j < count($index) - 1; $j++) {
            $temp = $index[$j];
            $index[$j] = $index[$j + 1];
            $index[$j + 1] = $temp;

            // print_r("rotate");
            // var_dump($index);

            // print_r("plantilla");
            // var_dump($arrTemp);

            $checkDif = array_map(function ($arr0, $arr1) {
                if (!is_null($arr1) && $arr1 == $arr0) {
                    return 'true';
                } else if (!is_null($arr1) && $arr1 != $arr0) {
                    return 'false';
                } else {
                    return 'null';
                }
            }, $index, $arrTemp);

            // print_r("check");
            // var_dump($checkDif);

            if (in_array('false', $checkDif)) {
                continue;
            } else {
                return $index;
            }
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

var_dump($puzzleAssembled);
die();

foreach ($puzzleAssembled as $key => $index) {
    $result .= $index . '\n';
}

fwrite($fOpen, $resultTitle);
fwrite($fOpen, $result);
fclose($fOpen);
