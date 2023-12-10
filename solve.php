<?php


// $w represents the array width the array return will content
// $h represents the array height the array return will content
// $puzzlePieces represents an array with $w * $h arrays inside

function getPuzzleAssemble($w, $h, $puzzlePieces, $puzzlePiecesTaken = array(), $skipLast = null)
{
    $leftSide = null;
    $puzzlePiecesPos = '';
    $puzzlePieceChoose = '';
    $temp = [null, null, null, null];

    for ($i = 0; $i < 2; $i++) {
        $topSide = array();
        for ($j = 0; $j < $w; $j++) {
            if (!isset($puzzlePiecesTaken[$i][$j])) {
                [$puzzlePiecesPos, $puzzlePieceChoose] = findAPiece($i, $j, $w, $h, $leftSide, $temp[$j], $puzzlePieces, $puzzlePiecesTaken, $skipLast);
                $leftSide = $puzzlePieceChoose[2];
                $topSide[] = $puzzlePieceChoose[3];
                $puzzlePiecesTaken[$i][] = $puzzlePiecesPos;
            }
        }
        $temp = $topSide;
    }

    return $puzzlePiecesTaken;
}


function findAPiece($x, $y, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken, $skipLast)
{
    if ($x == 0 && $y == 0) {
        return rotatePuzzle($w, $h, $puzzlePieces, 0, 0, null, null, $puzzlePiecesTaken, $skipLast);
    } else if ($y == ($w - 1) && $x == 0) {
        return rotatePuzzle($w, $h, $puzzlePieces, $leftSide, 0, 0, null, $puzzlePiecesTaken, $skipLast);
    } else if ($y == 0 && $x == ($h - 1)) {
        return rotatePuzzle($w, $h, $puzzlePieces, 0, $topSide, null, 0, $puzzlePiecesTaken, $skipLast);
    } else if ($y == ($w - 1) && $x == ($h - 1)) {
        return rotatePuzzle($w, $h, $puzzlePieces, $leftSide, $topSide, 0, 0, $puzzlePiecesTaken, $skipLast);
    } else if (($x == 0 && $y < $w && $y > 0 || $y == ($w - 1) && $x > 0  && $x < $h || $y == 0 && $x > 0 && $x < $h || $x == ($h - 1) && $y > 0 && $y < $w)) {
        return rotatePuzzle($w, $h, $puzzlePieces, ($y == 0) ? 0 : $leftSide, ($x == 0) ? 0 : $topSide, ($y == ($w - 1)) ? 0 : null, ($x == ($h - 1)) ? 0 : null, $puzzlePiecesTaken, $skipLast);
    } else {
        return rotatePuzzle($w, $h, $puzzlePieces, $leftSide, $topSide, null, null, $puzzlePiecesTaken, $skipLast);
    }
}

function rotatePuzzle($w, $h, $puzzle, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePiecesTaken, $skipLast)
{
    $arrTemp = [$leftSide, $topSide, $rightSide, $bottomSide];

    foreach ($puzzle as $key => $index) {
        if (!in_array($key + 1, $puzzlePiecesTaken) && $skipLast != ($key + 1)) {

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
                    } else if (!is_null($arr1) && $arr1 != $arr0 || is_null($arr1) && $arr0 == 0) {
                        return 'false';
                    }
                }, $index, $arrTemp);

                if (in_array('false', $checkDif)) {
                    continue;
                } else {
                    return [$key + 1, $index];
                }
            }
        }
    }
    $skipLast = $puzzlePiecesTaken[count($puzzlePiecesTaken) - 1];
    print_r("skip");
    print_r($skipLast);
    array_pop($puzzlePiecesTaken[count($puzzlePiecesTaken) - 1]);
    print_r("taken");
    var_dump($puzzlePiecesTaken);
    getPuzzleAssemble($w, $h, $puzzle, $puzzlePiecesTaken, $skipLast);
}


$fOpen = fopen('4x4.txt', 'a+');

while ($fGets = (fgets($fOpen))) {
    $puzzlePieces[] = explode(' ', $fGets);
}

[$w, $h] = $puzzlePieces[0];
array_shift($puzzlePieces);

$resultTitle = "\n\nSoluciones\n______________";

$puzzleAssembled = getPuzzleAssemble($w, $h, $puzzlePieces);
$result = '';
var_dump($puzzleAssembled);
die();

foreach ($puzzleAssembled as $key => $index) {
    foreach ($index as $key => $value) {
        $result .= $value . "\n";
    }
}

fwrite($fOpen, $resultTitle);
fwrite($fOpen, $result);
fclose($fOpen);



// $arr = [
//     [[1, 2, 1, 3], [4, 45, 5, 8], [7, 8, 9, 7], [4, 5, 8, 7]],
//     [[1, 2, 1, 3], [4, 45, 5, 8], [7, 8, 5, 4], [4, 5, 3, 2]]
// ];


// array_pop($arr[count($arr) - 1]);

// var_dump($arr);
