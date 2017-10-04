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
homepage=$(wp post create --post_type=page --post_title="Movie Database" --post_content="[add_movie_form]" --post_status=publish --porcelain)
wp option update show_on_front page
wp option update page_on_front $homepage

# Load movie ratings.
movie_rating=$(wp term create rating Universal --porcelain)
wp term meta set $movie_rating age_relation 0

movie_rating=$(wp term create rating 12A --porcelain)
wp term meta set $movie_rating age_relation 11

movie_rating=$(wp term create rating 12 --porcelain)
wp term meta set $movie_rating age_relation 12

movie_rating=$(wp term create rating 15 --porcelain)
wp term meta set $movie_rating age_relation 15

movie_rating=$(wp term create rating PG --porcelain)
wp term meta set $movie_rating age_relation 8

movie_rating=$(wp term create rating R18 --porcelain)
wp term meta set $movie_rating age_relation 18

movie_rating=$(wp term create rating 18 --porcelain)
wp term meta set $movie_rating age_relation 18

# Load a movie genre.
action_genre=$(wp term create genre Action --porcelain)
wp term meta update $action_genre genre_id 28

# Load a movie.
movie=$(wp post create --post_status=publish --post_type=movie --post_title="Deadpool" --post_content="Deadpool tells the origin story of former Special Forces operative turned mercenary Wade Wilson, who after being subjected to a rogue experiment that leaves him with accelerated healing powers, adopts the alter ego Deadpool. Armed with his new abilities and a dark, twisted sense of humor, Deadpool hunts down the man who nearly destroyed his life." --porcelain)
wp post term set $movie rating $movie_rating --by=id
wp post term set $movie genre $action_genre --by=id

echo "All done!"
