<?php
namespace CEmerson\TaxmanGame;

class TaxmanGame
{
    private $numbers = [];

    const STATE_AVAILABLE = 0;
    const STATE_UNAVAILABLE = 1;
    const STATE_MINE = 2;
    const STATE_TAXMANS = 3;

    public function __construct($numbers)
    {
        $this->numbers = array_fill(1, $numbers, self::STATE_AVAILABLE);
        $this->numbers[1] = self::STATE_UNAVAILABLE;
    }

    public function play($number)
    {
        $this->claimNumberAsMine($number);
        $this->markFactorsAsTaxmans($number);
        $this->recalculateAvailablePlays();
    }

    public function getNumbers()
    {
        return $this->numbers;
    }

    public function getNumberState($number)
    {
        return $this->numbers[$number];
    }

    public function getAvailablePlays()
    {
        return array_keys(
            array_filter($this->numbers, function ($element) {
                return $element == self::STATE_AVAILABLE;
            })
        );
    }

    private function markFactorsAsTaxmans($chosenNumber)
    {
        foreach (range(1, $chosenNumber) as $number) {
            if (
                $this->isFactorOfChosenNumber($number, $chosenNumber)
                && !$this->numberIsMine($number)
            ) {
                $this->claimNumberAsTaxmans($number);
            }
        }
    }

    private function isFactorOfChosenNumber($candidateFactor, $number)
    {
        return $number != $candidateFactor && $number % $candidateFactor == 0;
    }

    private function numberIsAvailable($candidateNumber)
    {
        return $this->getNumberState($candidateNumber) == self::STATE_AVAILABLE;
    }

    private function numberIsMine($candidateNumber)
    {
        return ($this->numbers[$candidateNumber] == self::STATE_MINE);
    }

    private function numberIsUnclaimed($candidateNumber)
    {
        return (!$this->numberIsMine($candidateNumber) && !$this->numberIsTaxmans($candidateNumber));
    }

    private function numberIsTaxmans($candidateNumber)
    {
        return ($this->numbers[$candidateNumber] == self::STATE_TAXMANS);
    }

    private function claimNumberAsMine($number)
    {
        if ($this->numberIsAvailable($number)) {
            $this->numbers[$number] = self::STATE_MINE;
        } else {
            throw new NumberNotAvailableToPlayException();
        }
    }

    private function claimNumberAsTaxmans($number)
    {
        $this->numbers[$number] = self::STATE_TAXMANS;
    }

    private function recalculateAvailablePlays()
    {
        foreach (array_keys($this->numbers) as $number) {
            if (!$this->stillHasFactorsLeft($number) && $this->getNumberState($number) == self::STATE_AVAILABLE) {
                $this->numbers[$number] = self::STATE_UNAVAILABLE;
            }
        }
    }

    public function getScores()
    {
        if (count($this->getAvailablePlays()) == 0) {
            $myScore = 0;
            $taxmansScore = 0;

            foreach ($this->numbers as $number => $state) {
                if ($state == self::STATE_MINE) {
                    $myScore += $number;
                }

                if ($state == self::STATE_TAXMANS) {
                    $taxmansScore += $number;
                }
            }

            return [$myScore, $taxmansScore];
        } else {
            throw new GameNotYetEndedException();
        }
    }

    private function stillHasFactorsLeft($candidateNumber)
    {
        if ($candidateNumber == 1) {
            return false;
        }

        foreach (range(1, $candidateNumber) as $number) {
            if ($this->isFactorOfChosenNumber($number, $candidateNumber) && $this->numberIsUnclaimed($number)) {
                return true;
            }
        }

        return false;
    }
}
