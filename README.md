
MDM Pre-Commit
=====================

What is it?
-----------

It's a tool ho can checks:
* git merge conflict markers
* Personalized debugging (JS,PHP) statements
* PHP Syntax Error
* XML Syntax Error
* YML Syntax Error

It optimizes PHP and reports about (All features are activated by default):
* Fixing coding standards issues (PHP Cs Fixer)
* Mess Detection (Unused code, complexity...)
* Copy/Paste detection

Install:
--------
Change PHP Cli Configuration with:
phar.readonly = Off

To install precommit tool and generate phar package:
launch install_precommit.sh
This script will install all required libraries and cretae a phar file with recent source

In your .git/hooks/pre-commit repository just add "precommit [options]"

Usage:
-----
After the installation you can execute ./precommit [options] to verify added git file. Well you can add the execution the phar in the .git add hook.

Options:
-------
When executing the precommit script you can use some options like:
* --with-pics                 Showing picture of status of commit (default: true)
* --php-cs-fixer-enable       Enabling php-cs-fixer when verifying files to commit (default: true)
* --php-cs-fixer-auto-git-add Enabling auto adding to git files after correction  (default: true)
* --php-cpd-enable            Enabling PHP Copy/Paste Detector when verifying files to commit (default: true)
* --php-cpd-min-lines         Minimum number of identical lines (default: 5)
* --php-cpd-min-token         Minimum number of identical tokens (default: 50)
* --php-md-enable             Enabling PHP Mess Detector when verifying files to commit (default: true)


Required Libraries
---------------------
Xmllint Install
apt-get install libxml2-utils

Composer Global Install
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

Composer libraries (composer.json):
 * PHP Cs Fixer Install (https://github.com/fabpot/PHP-CS-Fixer)
 * PHP Mess Detector
 * PHP CPD

TODO:
----
change the used cli libraries by php library that make the same thing (https://scrutinizer-ci.com/docs/tools/php/)
