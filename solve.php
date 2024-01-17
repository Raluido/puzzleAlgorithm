<?php


function getPuzzleAssemble($lastRowElement, $lastRow, $puzzlePieces)
{
    function assembling($lastRowElement, $lastRow, $puzzlePieces, $puzzlePieceSolveByNumber, $puzzlePieceSolveByPieces, &$results)
    {
        $leftSide = null;
        $topSide = null;
        $bottomSide = null;
        $rightSide = null;
        $puzzlePieceNextCandidatePosition = count($puzzlePieceSolveByNumber);

        if ($puzzlePieceNextCandidatePosition == ($lastRowElement * $lastRow)) {
            $results[] = $puzzlePieceSolveByNumber;
            $puzzlePieceSolveByNumber = array();
            $puzzlePieceSolveByPieces = array();
            return;
        }

        for ($puzzlePieceCandidate = 0; $puzzlePieceCandidate < count($puzzlePieces); $puzzlePieceCandidate++) {

            if (in_array($puzzlePieceCandidate + 1, $puzzlePieceSolveByNumber)) continue;


            if ($puzzlePieceNextCandidatePosition < $lastRowElement) {
                $topSide = 0;
            } else {
                $topSide = $puzzlePieceSolveByPieces[$puzzlePieceNextCandidatePosition - $lastRowElement][3];
            }

            if ($puzzlePieceNextCandidatePosition > ($lastRowElement * $lastRow - 1) - $lastRowElement) {
                $bottomSide = 0;
            }

            if ($puzzlePieceNextCandidatePosition % $lastRowElement == 0) {
                $leftSide = 0;
            } else {
                $leftSide = $puzzlePieceSolveByPieces[$puzzlePieceNextCandidatePosition - 1][2];
            }

            if ($puzzlePieceNextCandidatePosition % $lastRowElement == $lastRowElement - 1) {
                $rightSide = 0;
            }

            $puzzlePieceNeeded = [$leftSide, $topSide, $rightSide, $bottomSide];

            [$isFitted, $puzzlePiece, $side] = rotatePuzzle($puzzlePieces[$puzzlePieceCandidate], $puzzlePieceNeeded);

            if ($isFitted) {
                $puzzlePieceSolveByPieces[] = $puzzlePiece;
                $puzzlePieceSolveByNumber[] = $puzzlePieceCandidate + 1;
                assembling($lastRowElement, $lastRow, $puzzlePieces, $puzzlePieceSolveByNumber, $puzzlePieceSolveByPieces, $results);
                array_pop($puzzlePieceSolveByPieces);
                array_pop($puzzlePieceSolveByNumber);
            } else {
                continue;
            }
        }
    }

    function rotatePuzzle($piece, $pieceNeeded)
    {
        for ($side = 0; $side < 4; $side++) {

            if ($side > 0) {
                $temp0 = $piece[0];
                $piece[0] = $piece[3];
                $temp1 = $piece[1];
                $piece[1] = $temp0;
                $temp2 = $piece[2];
                $piece[2] = $temp1;
                $piece[3] = $temp2;
            }

            $checkDif = array_map(function ($arr0, $arr1) {
                if ($arr1 == $arr0 || is_null($arr1) && $arr0 != 0) {
                    return true;
                } else {
                    return false;
                }
            }, $piece, $pieceNeeded);

            if (in_array(false, $checkDif)) {
                continue;
            } else {
                return [true, $piece, $side];
            }
        }

        return [false, null, null];
    }

    $results = array();
    assembling($lastRowElement, $lastRow, $puzzlePieces, [], [], $results);
    return $results;
}


$fOpen = fopen('4x4.txt', 'a+');

while ($fGets = trim(fgets($fOpen))) {
    $puzzlePieces[] = explode(' ', $fGets);
}

[$w, $h] = $puzzlePieces[0];
array_shift($puzzlePieces);

$resultTitle = "\n\nSoluciones\n______________\n\n";

$puzzleAssembled = getPuzzleAssemble($w, $h, $puzzlePieces);
if (is_string($puzzleAssembled)) {
    $printResult = $puzzleAssembled;
} else {

    $result = 0;
    $printResult = '';

    while ($result < count($puzzleAssembled)) {
        $lastRowElement = $w;
        $lastRow = $h;
        $elementRow = 0;

        for ($rowsCounter = 0; $rowsCounter < $lastRow; $rowsCounter++) {
            $rows = '';
            for ($elementRowSelected = $elementRow; $elementRowSelected < $lastRowElement; $elementRowSelected++) {
                $rows .= $puzzleAssembled[$result][$elementRowSelected] . ' ';
            }
            $printResult .= $rows . "\n";
            $elementRow = $lastRowElement;
            $lastRowElement = $elementRow + $w;
        }
        $printResult .= "--------------\n\n";
        $result++;
    }
}

fwrite($fOpen, $resultTitle);
fwrite($fOpen, $printResult);
fclose($fOpen);
