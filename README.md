Taxman Game
===========

Implementation of the Taxman Game (found at <http://www.dsm.fordham.edu/~moniot/taxman.html>) used in order to try and
find an optimal solution.

Install PHP >= 5.6, install composer (<https://getcomposer.org/download/>), run `composer install` in this directory, then:

    php taxman.php n
    
Where n is the number of integers to start with. Takes a LONG time for even smallish n (20 or so), because it does an
exhaustive search of all possible plays. May run out of memory with large n... 20 took around 35MB or so.
