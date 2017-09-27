#!/bin/bash
set -e

# Composer.
cd /vagrant
composer install

# Configure WP.
wp plugin activate movieplugin
wp theme activate movietheme

echo "All done!"
