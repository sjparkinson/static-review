#!/bin/bash

# Install Pre-commit script for linux (debian/ubuntu)

# =============================
# INSTALL composer
# =============================

if ! type composer > /dev/null; then
echo "Composer Global install..."
sudo bash <<EOF
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar /usr/local/bin/composer
EOF
fi

# =============================
# INSTALL xmllint
# =============================

if ! type xmllint > /dev/null; then
echo "LibXml install..."
sudo bash <<EOF
	apt-get install libxml2
EOF
fi

# =============================
# INSTALL jsonlint
# =============================

if ! type jsonlint > /dev/null; then
echo "JsonLint install..."
sudo bash <<EOF
	apt-get install jsonlint
EOF
fi

# =============================
# INSTALL nodejs & npm & Eslint
# =============================

# see https://github.com/nodejs/node-v0.x-archive/wiki/Installing-Node.js-via-package-manager

# =============================
# INSTALL scss_lint
# =============================

if ! type gem > /dev/null; then
echo "rubygems install..."
sudo bash <<EOF
	apt-get install rubygems
EOF
fi

if ! type sass-convert > /dev/null; then
echo "sass install..."
sudo bash <<EOF
	gem install sass
EOF
fi

if ! type scss-lint > /dev/null; then
echo "scss-lint install..."
sudo bash <<EOF
	gem install scss_lint
EOF
fi

# =============================
# Copying rules
# =============================

if [ -d src/Rules ]; then
    if [ ! -d $HOME/.precommitRules ]; then
        mkdir $HOME/.precommitRules
    fi
    echo "updating precommit linter rules..."
    rsync -avh src/Rules/ $HOME/.precommitRules/ > /dev/null
fi

# =============================
# Composer dependencies
# =============================

composer global require 'sebastian/phpcpd=*'
composer global require 'fabpot/php-cs-fixer @stable'
composer global require 'phpmd/phpmd=@stable'
composer global require 'kherge/box=2.5.*'
composer global require 'squizlabs/php_codesniffer=2.*'

COMPOSERPATH=${HOME}/.composer/vendor/bin

if [ -d "$COMPOSERPATH" ] && [[ :$PATH: != *:"$COMPOSERPATH":* ]] ; then
        echo "Add export composer bin PATH on your .bashrc"
        echo "PATH=\$PATH:${COMPOSERPATH}"
        echo "And source it:"
        echo "source $HOME/.bashrc"
        echo "Re-execute ./install_precommit.sh"
        exit 0
fi

composer install

# =============================
# Build pre-commit
# =============================

box build

echo "Putting the precommit phar globally..."
echo "Moving precommit.phar to /usr/local/bin/precommit..."
sudo bash <<EOF
	mv precommit.phar /usr/local/bin/precommit
EOF
