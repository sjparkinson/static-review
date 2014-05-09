#!/bin/bash

# Install Script Pre-commit MDM

if ! type composer > /dev/null; then
echo "Composer Global install..."
sudo bash <<EOF
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar /usr/local/bin/composer
EOF
fi

composer global require 'sebastian/phpcpd=*'
composer global require 'fabpot/php-cs-fixer @stable'
composer global require 'phpmd/phpmd=1.4.*'

composer require 'symfony/console=2.4.*'
composer require 'symfony/yaml=2.4.*'

COMPOSERPATH=${HOME}/.composer/vendor/bin

if [ -d "$COMPOSERPATH" ] && [[ :$PATH: != *:"$COMPOSERPATH":* ]] ; then
	echo "export PATH=\$PATH:${COMPOSERPATH}" >> $HOME/.bashrc	
	echo "Mettez à jour votre PATH en executant la commande suivante: "
	echo "source $HOME/.bashrc"
fi


