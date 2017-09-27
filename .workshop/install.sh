#!/bin/bash
set -e

# Composer.
cd /vagrant
composer install

# Configure WP.
wp plugin activate movieplugin
wp theme activate movietheme
wp rewrite structure '/%year%/%monthnum%/%postname%'
wp rewrite flush

# Create a menu so people find content.
wp menu create "Navigation"
wp menu location assign navigation primary
wp menu item add-custom navigation Movies http://workshop.local/movies/

echo "All done!"
