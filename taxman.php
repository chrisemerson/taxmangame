<?php
ini_set('memory_limit', '2048M');

require_once __DIR__ . "/vendor/autoload.php";

use CEmerson\TaxmanGame\TaxmanGame;

foreach (range(intval($argv[1]), intval($argv[2])) as $number) {
    playGame($number);
}

function playGame($number)
{
    $startTime = time();

    $game = new TaxmanGame($number);
    $scores = playAllAvailablePlays($game);

    $maxScore = 0;
    $taxmanScoreAtMax = 0;
    $maxIndexes = [];

    foreach ($scores as $score) {
        if ($score['score'][0] > $maxScore) {
            $maxScore = $score['score'][0];
            $taxmanScoreAtMax = $score['score'][1];
        }
    }

    foreach ($scores as $index => $score) {
        if ($score['score'][0] == $maxScore) {
            $maxIndexes[] = $index;
        }
    }

    $endTime = time();

    echo "Best possible score with " . $number . " numbers is " . $maxScore . "-" . $taxmanScoreAtMax . " with " . count($maxIndexes) . " different sequences:" . PHP_EOL;

    foreach ($maxIndexes as $maxIndex) {
        echo implode(" ", $scores[$maxIndex]['plays']) . PHP_EOL;
    }

    echo "Played " . count($scores) . " games and took " . ($endTime - $startTime) . " seconds" . PHP_EOL . PHP_EOL;
}

function playAllAvailablePlays(TaxmanGame $game, $scores = [])
{
    $availablePlays = $game->getAvailablePlays();

    if (count($availablePlays) == 0) {
        $scores[] = [
            'score' => $game->getScores(),
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