<?php
namespace spec\CEmerson\TaxmanGame;

use CEmerson\TaxmanGame\GameNotYetEndedException;
use CEmerson\TaxmanGame\NumberNotAvailableToPlayException;
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

    function it_should_throw_an_exception_if_i_try_to_play_a_number_i_have_already_played()
    {
        $this->beConstructedWith(5);
        $this->play(4);

        $this->shouldThrow(NumberNotAvailableToPlayException::class)->during('play', [4]);
    }

    function it_should_throw_an_exception_if_i_try_to_play_a_number_the_taxman_has_claimed()
    {
        $this->beConstructedWith(5);
        $this->play(4);

        $this->shouldThrow(NumberNotAvailableToPlayException::class)->during('play', [2]);
    }

    function it_should_recalculate_which_numbers_are_available_after_a_play()
    {
        $this->beConstructedWith(6);
        $this->play(6);

        $this->getAvailablePlays()->shouldNotContain(4);
    }

    function it_should_throw_an_exception_if_i_try_to_play_a_number_that_has_no_factors_left_available()
    {
        $this->beConstructedWith(6);
        $this->play(6);

        $this->shouldThrow(NumberNotAvailableToPlayException::class)->during('play', [4]);
    }

    function it_should_throw_an_exception_if_you_try_and_get_the_scores_before_game_has_ended()
    {
        $this->beConstructedWith(5);

        $this->shouldThrow(GameNotYetEndedException::class)->during('getScores');
    }

    function it_should_return_the_scores_after_a_game_has_been_completed()
    {
        $this->beConstructedWith(2);

        $this->play(2);

        $this->getScores()->shouldReturn([2, 1]);
    }

    function it_should_play_a_full_complex_game()
    {
        $this->beConstructedWith(20);

        $this->play(11);
        $this->play(15);
        $this->play(4);
        $this->play(20);
        $this->play(14);
        $this->play(12);
        $this->play(18);
        $this->play(16);

        $this->getAvailablePlays()->shouldReturn([]);
        $this->getScores()->shouldReturn([110, 100]);
    }
}
