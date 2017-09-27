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

# Home page.
homepage=$(wp post create --post_type=page --post_title="Movie Schedule" --post_content="[add_movie_form]" --post_status=publish --porcelain)
wp option update show_on_front page
wp option update page_on_front $homepage

echo "All done!"
