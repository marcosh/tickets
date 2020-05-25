# tickets

Ticketing system domain developed for a job interview.

## tools

### composer

use `bin/composer` to manage project dependencies.

### composer require checker

use `bin/composerRequireChecker` to check that every used dependency is declared explicitly.

### tests

run the tests using `bin/kahlan`.

### linting

check linting rules (PSR-12) using `bin/phpcs`.

### static analysis

run `bin/psalm`

### file watcher

to get an immediate feedback at every file save from the above tool, run `sos`. This will use https://github.com/schell/steeloverseer (to be installed, this needs Haskell Stack)
