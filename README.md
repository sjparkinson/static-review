
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
* Composer Sync .json and .lock files

It optimizes PHP and reports about (All features are activated by default):
* Fixing coding standards issues (PHP Cs Fixer)
* Mess Detection (Unused code, complexity...)
* Copy/Paste detection

Install:
--------
Change PHP Cli Configuration with:
phar.readonly = Off

To install precommit tool and generate phar package:
Launch install script ./install_precommit.sh
This script will install all required libraries, create a phar file with sources files, and move it to /usr/local/bin

In your .git/hooks/pre-commit file ("cp pre-commit-sample pre-commit" if not exist), just add "precommit check [options]"

Usage:
-----
After the installation, you can execute "precommit" command to:
* check : Execute pre-commit
* checkFile [file] : Execute pre-commit checks on single file
* php-cs-fixer [file] : Execute php-cs-fixer rule on single file

Options:
-------
When executing the precommit and checkFile command you can use some options like:
* --php-cs-fixer-enable       Enabling php-cs-fixer when verifying files to commit (default: true)
* --php-cs-fixer-auto-git-add Enabling auto adding to git files after correction  (default: true)
* --php-cpd-enable            Enabling PHP Copy/Paste Detector when verifying files to commit (default: true)
* --php-md-enable             Enabling PHP Mess Detector when verifying files to commit (default: true)


Libraries
---------------------
 * libxml2-utils
 * PHP Cs Fixer Install (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
 * PHP Mess Detector
 * PHP CPD
