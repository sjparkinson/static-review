#!/bin/bash

# Install Pre-commit

if ! type composer > /dev/null; then
echo "Composer Global install..."
sudo bash <<EOF
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar /usr/local/bin/composer
EOF
fi

if ! type xmllint > /dev/null; then
echo "LibXml install..."
sudo bash <<EOF
	apt-get install libxml2
EOF
fi

if ! type jsonlint > /dev/null; then
echo "JsonLint install..."
sudo bash <<EOF
	apt-get install jsonlint
EOF
fi

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

box build

echo "Putting the precommit phar globally..."
echo "Moving precommit.phar to /usr/local/bin/precommit..."
sudo bash <<EOF
	mv precommit.phar /usr/local/bin/precommit
EOF
