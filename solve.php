<?php


// $w represents the array width the array return will content
// $h represents the array height the array return will content
// $puzzlePieces represents an array with $w * $h arrays inside

function getPuzzleAssemble($w, $h, $puzzlePieces, $puzzlePiecesTaken = array(), $puzzlePieceChoose = array(), $skipLast = null)
{
    $leftSide = null;
    $topSide = null;

    for ($i = 0; $i < 1; $i++) {
        for ($j = 0; $j < $w; $j++) {
            if (!isset($puzzlePiecesTaken[$i][$j])) {
                if ($j > 0 && isset($puzzlePieceChoose[$i][$j - 1])) {
                    $leftSide = $puzzlePieceChoose[$i][$j - 1][2];
                }
                if ($i > 0 && isset($puzzlePieceChoose[$i - 1][$j])) {
                    $topSide = $puzzlePieceChoose[$i - 1][$j][3];
                }
                [$puzzlePiecesTaken[], $puzzlePieceChoose[$i][]] = findAPiece($i, $j, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
            }
        }
    }

    return $puzzlePiecesTaken;
}


function findAPiece($x, $y, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose)
{
    if ($x == 0 && $y == 0) {
        return rotatePuzzle($w, $h, $puzzlePieces, 0, 0, null, null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if ($y == ($w - 1) && $x == 0) {
        return rotatePuzzle($w, $h, $puzzlePieces, $leftSide, 0, 0, null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if ($y == 0 && $x == ($h - 1)) {
        return rotatePuzzle($w, $h, $puzzlePieces, 0, $topSide, null, 0, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if ($y == ($w - 1) && $x == ($h - 1)) {
        return rotatePuzzle($w, $h, $puzzlePieces, $leftSide, $topSide, 0, 0, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if (($x == 0 && $y < $w && $y > 0 || $y == ($w - 1) && $x > 0  && $x < $h || $y == 0 && $x > 0 && $x < $h || $x == ($h - 1) && $y > 0 && $y < $w)) {
        return rotatePuzzle($w, $h, $puzzlePieces, ($y == 0) ? 0 : $leftSide, ($x == 0) ? 0 : $topSide, ($y == ($w - 1)) ? 0 : null, ($x == ($h - 1)) ? 0 : null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else {
        return rotatePuzzle($w, $h, $puzzlePieces, $leftSide, $topSide, null, null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    }
}

function rotatePuzzle($w, $h, $puzzle, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose)
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
                    if ($arr1 != null && $arr1 == $arr0) {
                        print_r("true");
                        return true;
                    } else if ($arr1 != null && $arr1 != $arr0) {
                        return false;
                    } else {
                        return null;
                    }
                }, $index, $arrTemp);

                // var_dump($index);
                // var_dump($arrTemp);

                if (in_array(false, $checkDif)) {
                    continue;
                } else {
                    return [$key + 1, $index];
                }
            }
        }
    }

    if (end($puzzlePiecesTaken)) {
        $skipLast = end($puzzlePiecesTaken);
        array_pop($puzzlePiecesTaken);
    }

    if (count($puzzlePieceChoose) > 0) {
        array_pop($puzzlePieceChoose[count($puzzlePieceChoose) - 1]);
        if (count($puzzlePieceChoose[count($puzzlePieceChoose) - 1]) == 0) {
            array_pop($puzzlePieceChoose);
        }
    }

    getPuzzleAssemble($w, $h, $puzzle, $puzzlePiecesTaken, $puzzlePieceChoose, $skipLast);
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

// print_r(end(end($arr)));


// array_pop($arr[count($arr) - 1]);

// var_dump($arr);


// $arr = [true, true, true, true];

// if (in_array(false, $arr)) {
//     print_r("yes");
// }
