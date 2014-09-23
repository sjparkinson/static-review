# -*- mode: ruby -*-
# vi: set ft=ruby :

=begin
 This file is part of StaticReview

 Copyright (c) 2014 Samuel Parkinson <@samparkinson_>

 For the full copyright and license information, please view the LICENSE
 file that was distributed with this source code.

 @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
=end

Vagrant.configure("2") do |config|

  # Box
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  # Shared folders
  config.vm.synced_folder ".", "/srv"

  # Setup
  config.vm.provision :shell, :inline => "apt-get update --fix-missing"
  config.vm.provision :shell, :inline => "apt-get install -q -y cowsay python-software-properties python g++ make git curl"
  config.vm.provision :shell, :inline => "add-apt-repository ppa:ondrej/php5"
  config.vm.provision :shell, :inline => "apt-get update"
  config.vm.provision :shell, :inline => "apt-get install -q -y php5-cli php5-curl php5-xdebug"
  config.vm.provision :shell, :inline => "curl -sS https://getcomposer.org/installer | php"
  config.vm.provision :shell, :inline => "mv ./composer.phar /usr/local/bin/composer"

  # Done
  config.vm.provision :shell, :inline => "cowsay \"Your development environment is ready!\""

end
