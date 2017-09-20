#!/bin/bash
set -e

# Selenium - start with "selenium-standalone start"
npm install selenium-standalone@latest -g
selenium-standalone install

# Composer
cd /vagrant
composer install
