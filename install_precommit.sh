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

COMPOSERPATH=${HOME}/.composer/vendor/bin

if [ -d "$COMPOSERPATH" ] && [[ :$PATH: != *:"$COMPOSERPATH":* ]] ; then
	echo "export PATH=\$PATH:${COMPOSERPATH}" >> $HOME/.bashrc	
	echo "Mettez à jour votre PATH en executant la commande suivante: "
	echo "source $HOME/.bashrc"
	echo " Relancez le script d'installation ensuite..."
        exit 0
fi

composer install
box build

echo "Putting the precommit phar globally..."
sudo bash <<EOF
	mv precommit.phar /usr/local/bin/precommit
EOF
