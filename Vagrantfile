# -*- mode: ruby -*-
# vi: set ft=ruby :

=begin
 This file is part of StaticReview

 Copyright (c) 2014 Samuel Parkinson <@samparkinson_>

 For the full copyright and license information, please view the LICENSE
 file that was distributed with this source code.

 @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
=end

# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    hostname = "php.box"
    locale = "en_GB.UTF.8"

    # Box
    config.vm.box = "ubuntu/trusty64"

    # Shared folders
    config.vm.synced_folder ".", "/srv"

    # Setup
    config.vm.provision :shell, :inline => "touch .hushlogin"
    config.vm.provision :shell, :inline => "hostnamectl set-hostname #{hostname} && locale-gen #{locale}"
    config.vm.provision :shell, :inline => "apt-get update --fix-missing"
    config.vm.provision :shell, :inline => "apt-get install -q -y g++ make git curl vim"

    # Lang
    config.vm.provision :shell, :inline => "add-apt-repository ppa:ondrej/php && apt-get update"
    config.vm.provision :shell, :inline => "apt-get install -q -y php7.0-dev php7.0-cli php7.0-curl php-xdebug"
    config.vm.provision :shell, :inline => "curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"

    # Git Setup
    config.vm.provision :shell, :privileged => false, :inline => "git config --global user.name vagrant"
    config.vm.provision :shell, :privileged => false, :inline => "git config --global user.email vagrant@localhost"

end
