
MDM Pre-Commit
=====================

What is it?
-----------

This Code Reviewing tool can check:

> * GIT merge conflict markers
> * GIT NOCOMMIT markers
> * Forgot debugging (JS,PHP) statements
> * PHP Syntax Error
> * XML Syntax Error
> * YML Syntax Error
> * JSON Syntax Error
> * JS Syntax Error & auto-fix it
> * Composer Sync .json and .lock files

It optimizes PHP Syntax :

> * Automatically Fix coding standards issues (PHP CS Fixer)

It optimizes SCSS Syntax :

> * Automatically Fix coding standards issues (Sass-convert)

It reports about bad JS statements (See Rules/.eslintrc)

It reports about bad SCSS statements (See Rules/.scsslint.yml)

It reports about bad PHP statements :

> * Mess Detection (Unused code, complexity...) (See Rules/phpmd.xml)
> * Copy/Paste detection
> * Code sniffing to notify missing PHPDOC function

How to Install:
--------
Change PHP Cli Configuration with: 
```
phar.readonly = Off
```

To install precommit tool and generate phar package:
Launch install script ```./install_precommit.sh```
> This script will install all required libraries, create a phar file with sources files, and move it to /usr/local/bin

In your project path, just execute install command ```precommit install```

To know which project is configured with pre-commit, you can simply execute ```precommit listRepo [WORKSPACE_PATH] ```

How to Update:
--------

Get latest libraries version with a "composer global update"

Just update your GIT fork and re-launch install script.

Usage:
-----
After the installation, you can execute "precommit" command to:
> * check : Execute pre-commit
> * checkFile [file] : Execute pre-commit checks on single file
> * php-cs-fixer [file] : Execute php-cs-fixer rule on single file
> * install : Install pre-commit hook on your current project
> * listRepo [workspace_path] : Analyse all git projects to get pre-commit informations


PHPStorm Integration to check file manually:
-----
 * Go to Settings / External Tools
 * Add new one and fill info like this:

> * Program: "precommit"
> * Parameters: "checkFile $FilePath$"
> * Working Directory: "$ProjectFileDir$"


Used Libraries:
---------------------
 * libxml2-utils
 * PHP Cs Fixer Install (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
 * PHP Mess Detector
 * PHP CPD
 * PHPCS
 * ESLint (http://eslint.org/)
 * sass
 * scss-lint (https://github.com/brigade/scss-lint)
