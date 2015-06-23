<?php
namespace spec\CEmerson\TaxmanGame;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaxmanGameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CEmerson\TaxmanGame\TaxmanGame');
    }
}
