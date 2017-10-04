## Installation

1. If you aren't using Virtualbox and MacOS, you're on your own.
1. Check you meet [Chassis' requirements](http://docs.chassis.io/en/latest/quickstart/). You also need [NPM](https://nodejs.org/en/) installed (on your main OS, not inside the VM).
1. Run: `git clone --recursive https://github.com/humanmade/retreat-2017-automated-testing.git automated-testing`
1. Run: `cd automated-testing`
1. Run: `vagrant up`
1. Confirm you can load the site at http://workshop.local in your favourite web browser.
 * If you are proxied, remember to add `workshop.local` to MacOS' "Bypass settings for these Hosts & Domains" option in Network Settings.
1. Run: `npm install selenium-standalone@latest -g`
1. Run: `selenium-standalone install`
1. Go into the VM: `vagrant ssh`
1. Run: `/etc/workshop/install.sh`

