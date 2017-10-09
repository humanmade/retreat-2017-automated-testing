#!/bin/bash
set -e

# Composer.
cd /vagrant
composer install

# Configure WP.
wp site empty --yes
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

# Load movie genres.
movie_genre=$(wp term create genre "Action" --porcelain)
wp term meta set $movie_genre json_id 28

movie_genre=$(wp term create genre "Adventure" --porcelain)
wp term meta set $movie_genre json_id 12

movie_genre=$(wp term create genre "Animation" --porcelain)
wp term meta set $movie_genre json_id 16

movie_genre=$(wp term create genre "Comedy" --porcelain)
wp term meta set $movie_genre json_id 35

movie_genre=$(wp term create genre "Crime" --porcelain)
wp term meta set $movie_genre json_id 80

movie_genre=$(wp term create genre "Documentary" --porcelain)
wp term meta set $movie_genre json_id 99

movie_genre=$(wp term create genre "Drama" --porcelain)
wp term meta set $movie_genre json_id 18

movie_genre=$(wp term create genre "Family" --porcelain)
wp term meta set $movie_genre json_id 10751

movie_genre=$(wp term create genre "Fantasy" --porcelain)
wp term meta set $movie_genre json_id 14

movie_genre=$(wp term create genre "History" --porcelain)
wp term meta set $movie_genre json_id 36

movie_genre=$(wp term create genre "Horror" --porcelain)
wp term meta set $movie_genre json_id 27

movie_genre=$(wp term create genre "Music" --porcelain)
wp term meta set $movie_genre json_id 10402

movie_genre=$(wp term create genre "Mystery" --porcelain)
wp term meta set $movie_genre json_id 9648

movie_genre=$(wp term create genre "Romance" --porcelain)
wp term meta set $movie_genre json_id 10749

movie_genre=$(wp term create genre "Science Fiction" --porcelain)
wp term meta set $movie_genre json_id 878

movie_genre=$(wp term create genre "TV Movie" --porcelain)
wp term meta set $movie_genre json_id 10770

movie_genre=$(wp term create genre "Thriller" --porcelain)
wp term meta set $movie_genre json_id 53

movie_genre=$(wp term create genre "War" --porcelain)
wp term meta set $movie_genre json_id 10752

movie_genre=$(wp term create genre "Western" --porcelain)
wp term meta set $movie_genre json_id 37

# Load movies.
#wp movie populate

echo "All done!"
