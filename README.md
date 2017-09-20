# Chassis

Chassis is a virtual server for your WordPress site, built using [Vagrant][].

Chassis is basically a way to run WordPress (and related parts, such as PHP and
nginx) without needing to worry about setting up anything. You can imagine it as
MAMP/WAMP on steroids.

[Vagrant]: http://vagrantup.com/

## Installation

0. If you aren't using Virtualbox and MacOS, you're on your own.
1. Follow Chassis' [quickstart instructions](http://docs.chassis.io/en/latest/quickstart/).
2. Confirm you can load the site at http://workshop.local
 * If you are proxied, remember to add `workshop.local` to MacOS' "Bypass settings for these Hosts & Domains" option in Network Settings.
3. SSH into the VM: `vagrant ssh`
4. Run: `/etc/workshop/install.sh`
