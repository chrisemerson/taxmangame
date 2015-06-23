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

    public function play($number)
    {
        $this->claimNumberAsMine($number);
        $this->markFactorsAsTaxmans($number);
        $this->findAvailablePlays();
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

    private function isFactorOfChosenNumber($candidateNumber, $chosenNumber)
    {
        return $chosenNumber % $candidateNumber == 0;
    }

    private function numberIsAvailable($candidateNumber)
    {
        return $this->getNumberState($candidateNumber) == self::STATE_AVAILABLE;
    }

    private function numberIsMine($candidateNumber)
    {
        return $this->numbers[$candidateNumber] == self::STATE_MINE;
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

    private function findAvailablePlays()
    {
    }
}
