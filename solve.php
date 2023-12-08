<?php


// $w represents the array width the array return will content
// $h represents the array height the array return will content
// $puzzlePieces represents an array with $w * $h arrays inside

function getPuzzleAssemble($w, $h, $puzzlePieces)
{
    $puzzlePiecesTaken = array();
    $leftSide = null;
    $topSide = null;
    $puzzlePiecesPos = '';

    for ($i = 0; $i < $h; $i++) {
        for ($j = 0; $j < $w; $j++) {
            [$puzzlePiecesPos, $puzzleComplete[$i][]] = findAPiece($i, $j, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken);
            $leftSide = $puzzleComplete[$i][$j][2];
            $topSide = $puzzleComplete[$i][$j][3];
            $puzzlePiecesTaken[] = $puzzlePiecesPos;
        }
    }
    return $puzzleComplete;
}


function findAPiece($x, $y, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken)
{
    if ($x == 0 && $y == 0) {
        // print_r("find a piece");
        return rotatePuzzle($puzzlePieces, 0, 0, null, null, $puzzlePiecesTaken);
    } else if ($y == $w && $x == 0) {
        return rotatePuzzle($puzzlePieces, $leftSide, 0, 0, null, $puzzlePiecesTaken);
    } else if ($y == 0 && $x == $h) {
        return rotatePuzzle($puzzlePieces, 0, $topSide, null, 0, $puzzlePiecesTaken);
    } else if ($y == $w && $x == $h) {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, 0, 0, $puzzlePiecesTaken);
    } else if (($x == 0 && $y < $w && $y > 0 || $y == $w && $x > 0  && $x < $h || $y == 0 && $x > 0 && $x < $h || $x == $h && $y > 0 && $y < $w)) {
        return rotatePuzzle($puzzlePieces, ($y == 0) ? 0 : $leftSide, ($x == 0) ? 0 : $topSide, ($y == $w) ? 0 : null, ($x == $h) ? 0 : null, $puzzlePiecesTaken);
    } else {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, null, null, $puzzlePiecesTaken);
    }
}

function rotatePuzzle($puzzle, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePiecesTaken)
{
    $arrTemp = [$leftSide, $topSide, $rightSide, $bottomSide];

    foreach ($puzzle as $key => $index) {
        if (!in_array($key + 1, $puzzlePiecesTaken)) {
            // print_r("piecesTaken");
            // var_dump($puzzlePiecesTaken);

            for ($j = 0; $j < 4; $j++) {

                if ($j > 0) {
                    $temp0 = $index[0];
                    $index[0] = $index[3];
                    $temp1 = $index[1];
                    $index[1] = $temp0;
                    $temp2 = $index[2];
                    $index[2] = $temp1;
                    $index[3] = $temp2;
                }

                $checkDif = array_map(function ($arr0, $arr1) {
                    if (!is_null($arr1) && $arr1 == $arr0) {
                        return 'true';
                    } else if (!is_null($arr1) && $arr1 != $arr0) {
                        return 'false';
                    } else {
                        return 'null';
                    }
                }, $index, $arrTemp);

                if (in_array('false', $checkDif)) {
                    continue;
                } else {
                    // print_r("rotate");
                    // var_dump($index);
                    return [$key + 1, $index];
                }
            }
        }
    }
    print_r("no encontrado");
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
