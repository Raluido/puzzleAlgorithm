<?php


// $w represents the array width the array return will content
// $h represents the array height the array return will content
// $puzzlePieces represents an array with $w * $h arrays inside

function getPuzzleAssemble($w, $h, $puzzlePieces, $puzzlePiecesTaken = array(), $puzzlePieceChoose = array(), $skipLast = '')
{
    $leftSide = null;
    $topSide = null;

    // var_dump($puzzlePiecesTaken);
    // print_r("ooohhhh");

    for ($i = 0; $i < $h; $i++) {
        for ($j = 0; $j < $w; $j++) {
            if ($j > 0 && isset($puzzlePieceChoose[$i][$j - 1])) {
                $leftSide = $puzzlePieceChoose[$i][$j - 1][2];
            }
            if ($j == 0) {
                $leftSide = null;
            }
            if ($i > 0 && isset($puzzlePieceChoose[$i - 1][$j])) {
                $topSide = $puzzlePieceChoose[$i - 1][$j][3];
            }
            if ($i == 0) {
                $topSide = null;
            }

            // var_dump($puzzlePieceChoose);

            // print_r('i :');
            // print_r($i);
            // print_r('j :');
            // print_r($j);

            // print_r('leftSide ');
            // print_r($leftSide);
            // print_r('topSide ');
            // print_r($topSide);

            // var_dump($puzzlePiecesTaken[$i][$j]);
            // print_r("aqui con " . $i . " " . $j);

            $puzzleComplete = findAPiece($i, $j, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken, $puzzlePieceChoose, $skipLast);


            if ($puzzleComplete[0]) {
                [$result, $puzzlePiecesTaken[], $puzzlePieceChoose[$i][]] = $puzzleComplete;
                // if ($puzzlePiecesTaken[4] == 14) {
                //     var_dump($puzzlePiecesTaken);
                //     // die();
                // }
                // var_dump($puzzlePiecesTaken);
            } else {
                [$result, $puzzlePiecesTaken, $puzzlePieceChoose, $skipLast] = $puzzleComplete;

                if ($i == 0 && $j == 0) {
                    return "No hemos encontrado una ficha para iniciar el puzzle en la esquina superior izquierda.";
                } else if ($i >= 0 && $j > 1) {
                    $j -= 2;
                } else if ($i > 0 && $j < 2) {
                    $j = $w - 1;
                    $i -= 1;
                }
                // print_r("aqui");
                // print_r('i :');
                // print_r($i);
                // print_r('j :');
                // print_r($j);
            }
            // var_dump($puzzlePiecesTaken);
            // [$puzzlePiecesTaken[], $puzzlePieceChoose[$i][]] = findAPiece($i, $j, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);

            // print_r('i :' . $i);
            // print_r('j :' . $j);
            //var_dump($puzzlePiecesTaken);
        }
    }

    // print_r("estoooo");
    // var_dump($puzzleComplete);
    // die();

    return $puzzlePiecesTaken;
}


function findAPiece($x, $y, $w, $h, $leftSide, $topSide, $puzzlePieces, $puzzlePiecesTaken, $puzzlePieceChoose, $skipLast)
{
    // print_r('x :' . $x);
    // print_r('y :' . $y);
    if ($x == 0 && $y == 0) {
        return rotatePuzzle($puzzlePieces, 0, 0, null, null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if ($y == ($w - 1) && $x == 0) {
        return rotatePuzzle($puzzlePieces, $leftSide, 0, 0, null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if ($y == 0 && $x == ($h - 1)) {
        return rotatePuzzle($puzzlePieces, 0, $topSide, null, 0, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if ($y == ($w - 1) && $x == ($h - 1)) {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, 0, 0, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else if (($x == 0 && $y < ($w - 1) && $y > 0 || $y == ($w - 1) && $x > 0  && $x < ($h - 1) || $y == 0 && $x > 0 && $x < ($h - 1) || $x == ($h - 1) && $y > 0 && $y < ($w - 1))) {
        return rotatePuzzle($puzzlePieces, ($y == 0) ? 0 : $leftSide, ($x == 0) ? 0 : $topSide, ($y == ($w - 1)) ? 0 : null, ($x == ($h - 1)) ? 0 : null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    } else {
        return rotatePuzzle($puzzlePieces, $leftSide, $topSide, null, null, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose);
    }
}

function rotatePuzzle($puzzlePieces, $leftSide, $topSide, $rightSide, $bottomSide, $puzzlePiecesTaken, $skipLast, $puzzlePieceChoose)
{
    $arrTemp = [$leftSide, $topSide, $rightSide, $bottomSide];

    foreach ($puzzlePieces as $key => $index) {
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
                        return true;
                    } else if (!is_null($arr1) && $arr1 != $arr0 || is_null($arr1) && $arr0 == 0) {
                        return false;
                    } else if (is_null($arr1) && $arr0 != 0) {
                        return true;
                    }
                }, $index, $arrTemp);

                // var_dump($index);
                // var_dump($arrTemp);
                // var_dump($checkDif);

                // print_r("yeah");

                // var_dump($arrTemp);

                // if ($puzzlePiecesTaken[3] == 7 && $key == 14) {
                //     var_dump($puzzlePiecesTaken);
                //     var_dump($index);
                //     var_dump($arrTemp);
                //     die();
                // }

                if (in_array(false, $checkDif)) {
                    // var_dump($arrTemp);
                    continue;
                } else {

                    $skipLast = '';
                    // var_dump($key + 1);
                    return [true, $key + 1, $index];
                }
            }
        }
    }

    if (end($puzzlePiecesTaken)) {
        $skipLast = end($puzzlePiecesTaken);
        array_pop($puzzlePiecesTaken);
    }
    // if ($puzzlePiecesTaken[7] == 11) {
    //     var_dump($puzzlePieceChoose);
    //     print_r(count($puzzlePieceChoose) - 1);
    //     die();
    // }

    if (count($puzzlePieceChoose) > 0) {
        var_dump($puzzlePieceChoose);
        print_r(count($puzzlePieceChoose) - 1);
        array_pop($puzzlePieceChoose[count($puzzlePieceChoose) - 1]);
        if (count($puzzlePieceChoose[count($puzzlePieceChoose) - 1]) == 0) {
            array_pop($puzzlePieceChoose);
        }
    }

    return [false, $puzzlePiecesTaken, $puzzlePieceChoose, $skipLast];

    // getPuzzleAssemble($w, $h, $puzzle, $puzzlePiecesTaken, $puzzlePieceChoose, $skipLast);
}


$fOpen = fopen('4x4.txt', 'a+');

while ($fGets = trim(fgets($fOpen))) {
    $puzzlePieces[] = explode(' ', $fGets);
}

[$w, $h] = $puzzlePieces[0];
array_shift($puzzlePieces);

$resultTitle = "\n\nSoluciones\n______________";

$puzzleAssembled = getPuzzleAssemble($w, $h, $puzzlePieces);
$result = '';
//var_dump($puzzleAssembled);
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

// if (!isset($arr[0][4])) {
//     print_r("yes");
// } else {
//     print_r("not");
// }


// array_pop($arr[count($arr) - 1]);

// var_dump($arr);


// $arr = [true, true, true, true];

// if (in_array(false, $arr)) {
//     print_r("yes");
// }


// $arr = [null, true, true, true];

// if (in_array(false, $arr)) {
//     print_r("in array");
// } else {
//     print_r("not in array");
// }
