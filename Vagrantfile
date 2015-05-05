# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    version = "php5-5.6"
    hostname = "php.box"
    locale = "en_GB.UTF.8"

    # Box
    config.vm.box = "ubuntu/trusty64"

    # Shared folders
    config.vm.synced_folder ".", "/srv"

    # Setup
    config.vm.provision :shell, :inline => "touch .hushlogin"
    config.vm.provision :shell, :inline => "hostname #{hostname} && locale-gen #{locale}"
    config.vm.provision :shell, :inline => "apt-get update --fix-missing"
    config.vm.provision :shell, :inline => "apt-get install -q -y g++ make git curl vim"

    # Lang
    config.vm.provision :shell, :inline => "add-apt-repository ppa:ondrej/#{version} && apt-get update"
    config.vm.provision :shell, :inline => "apt-get install -q -y php5-dev php5-cli php5-curl php5-xdebug"
    config.vm.provision :shell, :inline => "curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"

end
