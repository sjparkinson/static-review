
MDM Pre-Commit
=====================

What is it?
-----------

This Code Reviewing tool can check:

* GIT merge conflict markers
* GIT NOCOMMIT markers
* Forgot debugging (JS,PHP) statements
* PHP Syntax Error
* XML Syntax Error
* YML Syntax Error
* Composer Sync .json and .lock files

It specifically optimizes PHP and reports about :

* Fixing coding standards issues (PHP CS Fixer)
* Mess Detection (Unused code, complexity...)
* Copy/Paste detection
* Code sniffing to notify missing PHPDOC function

Install:
--------
Change PHP Cli Configuration with:
**phar.readonly = Off**

To install precommit tool and generate phar package:
Launch install script ./install_precommit.sh
This script will install all required libraries, create a phar file with sources files, and move it to /usr/local/bin

In your **.git/hooks/pre-commit** file ("cp pre-commit-sample pre-commit" if not exist), just add: **precommit check**

Usage:
-----
After the installation, you can execute "precommit" command to:
* check : Execute pre-commit
* checkFile [file] : Execute pre-commit checks on single file
* php-cs-fixer [file] : Execute php-cs-fixer rule on single file

PHPStorm Integration to check file manually:
-----
* Go to Settings / External Tools
Add new one and fill info like this:
* Program: "precommit"
* Parameters: "checkFile $FilePath$"
* Working Directory: "$ProjectFileDir$"


Used Libraries:
---------------------
 * libxml2-utils
 * PHP Cs Fixer Install (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
 * PHP Mess Detector
 * PHP CPD
 * PHPCS
