<?php
require_once __DIR__ . "/vendor/autoload.php";

use CEmerson\TaxmanGame\TaxmanGame;

playGame(intval($argv[1]));

function playGame($number)
{
    $game = new TaxmanGame($number);
    $scores = playAllAvailablePlays($game);

    $maxScore = 0;
    $maxIndex = 0;

    foreach ($scores as $index => $score) {
        if ($score['score'] > $maxScore) {
            $maxScore = $score['score'];
            $maxIndex = $index;
        }
    }

    echo "Best possible score with " . $number . " numbers is " . $maxScore . " with sequence " . implode(" ", $scores[$maxIndex]['plays']) . PHP_EOL;
}

function playAllAvailablePlays(TaxmanGame $game, $scores = [])
{
    $availablePlays = $game->getAvailablePlays();

    if (count($availablePlays) == 0) {
        $scores[] = [
            'score' => $game->getScores()[0],
            'plays' => $game->getPlaySequence()
        ];
    } else {
        foreach ($availablePlays as $availablePlay) {
            $clonedGame = clone $game;
            $clonedGame->play($availablePlay);

            $scores = playAllAvailablePlays($clonedGame, $scores);
        }
    }

    return $scores;
}