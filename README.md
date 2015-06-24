Taxman Game
===========

Implementation of the Taxman Game (found at <http://www.dsm.fordham.edu/~moniot/taxman.html>) used in order to try and
find an optimal solution.

Install PHP >= 5.5, install composer (<https://getcomposer.org/download/>), run

    composer install

in this directory, then

    php taxman.php <n> <m>

...to play the game with all integers between n and m. Takes a LONG time for even smallish numbers (20 or so), because
it does an exhaustive search of all possible plays. May run out of memory with large numbers... 20 took around 35MB or
so.

If you play with the code at all, you can run the tests just to make sure you haven't broken its behaviour in any way

    bin/phpspec run

Have fun!
