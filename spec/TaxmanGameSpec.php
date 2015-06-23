<?php
namespace spec\CEmerson\TaxmanGame;

use CEmerson\TaxmanGame\TaxmanGame;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaxmanGameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType(TaxmanGame::class);
    }

    function it_is_able_to_start_a_game()
    {
        $this->beConstructedWith(20);

        $this->getNumbers()->shouldHaveCount(20);
    }

    function it_should_mark_1_as_unavailable_at_the_start_of_a_game()
    {
        $this->beConstructedWith(5);

        $this->getNumberState(1)->shouldBe(TaxmanGame::STATE_UNAVAILABLE);
    }

    function it_should_return_available_plays_when_asked_at_the_start_of_a_game()
    {
        $this->beConstructedWith(5);
        $this->getAvailablePlays()->shouldReturn([2, 3, 4, 5]);
    }

    function it_should_mark_a_number_as_mine_when_i_play_it()
    {
        $this->beConstructedWith(5);
        $this->play(4);

        $this->getNumberState(4)->shouldBe(TaxmanGame::STATE_MINE);
    }

    function it_should_mark_all_available_factors_as_taxmans_when_i_play_a_number()
    {
        $this->beConstructedWith(15);
        $this->play(12);

        $this->getNumberState(6)->shouldBe(TaxmanGame::STATE_TAXMANS);
        $this->getNumberState(4)->shouldBe(TaxmanGame::STATE_TAXMANS);
        $this->getNumberState(3)->shouldBe(TaxmanGame::STATE_TAXMANS);
        $this->getNumberState(2)->shouldBe(TaxmanGame::STATE_TAXMANS);
        $this->getNumberState(1)->shouldBe(TaxmanGame::STATE_TAXMANS);
    }
}
