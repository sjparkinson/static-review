MDM Pre-Commit
=====================

Precommit Tools to lint/review/clean all your files (php/js/scss/xml/json/scss)

## Table of Contents

1. [What is it?](#what-is-it)
1. [Requirements](#requirements)
1. [How to Install](#how-to-install)
1. [How to Update](#how-to-update)
1. [Usage](#usage)
1. [PHPStorm Integration](#phpstorm-integration)
1. [Used Libraries](#used-libraries)

## What is it<a name="what-is-it"></a>

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

It optimizes PHP Syntax with specific [php-cs-fixer rules](doc/fixer/php_cs_fixer_rules.md) (See PhpCsFixerReview.php) :

> * Automatically Fix coding standards issues (PHP CS Fixer)

It optimizes SCSS Syntax :

> * Automatically Fix coding standards issues (Sass-convert)

It reports about bad JS statements with specific [eslint rules](doc/fixer/js_eslint_rules.md) (See Rules/.eslintrc)

It reports about bad SCSS statements specific [scss-lint rules](doc/fixer/scss_lint_rules.md) (See Rules/.scsslint.yml)

It reports about bad PHP statements :

> * Mess Detection (Unused code, complexity...) with [phpmd rules](doc/fixer/php_md_rules.md) (See Rules/phpmd.xml)
> * Copy/Paste detection
> * Code sniffing to notify missing PHPDOC function

## Requirements<a name="requirements"></a>

Execute check-requirements command:
```./bin/precommit checkRequirements```

Change PHP Cli Configuration if necessary with: 
```
phar.readonly = Off
```

## How to Install<a name="how-to-install"></a>

To install precommit tool and generate phar package:
Launch install script ```./install_precommit.sh```
> This script will install all required libraries, create a phar file with sources files, and move it to /usr/local/bin

In your project path, just execute install command ```precommit install```

To know which project is configured with pre-commit, you can simply execute ```precommit listRepo [WORKSPACE_PATH] ```

## How to Update<a name="how-to-update"></a>

Get latest libraries version with a "composer global update"

Just update your GIT fork and re-launch install script.

## Usage<a name="usage"></a>

After the installation, you can execute "precommit" command to:
> * **checkRequirements** : Execute check requirements
> * **check** : Execute pre-commit
> * **checkFile [file]** : Execute pre-commit checks on single file
> * **php-cs-fixer [file]** : Execute php-cs-fixer rule on single file
> * **install** : Install pre-commit hook on your current project
> * **listRepo [workspace_path]** : Analyse all git projects to get pre-commit informations


## PHPStorm Integration<a name="phpstorm-integration"></a>

In order to check file manually in PhpStorm:

* Go to Settings / External Tools
* Add new one and fill info like this:
  * Program: "precommit"
  * Parameters: "checkFile $FilePath$"
  * Working Directory: "$ProjectFileDir$"


## Used Libraries<a name="used-libraries"></a>

* libxml2-utils
* PHP Cs Fixer Install (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
* PHP Mess Detector
* PHP CPD
* PHPCS
* ESLint (http://eslint.org/)
* sass
* scss-lint (https://github.com/brigade/scss-lint)
