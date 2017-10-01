<?php

define( 'DB_NAME',     'wordpress' );
define( 'DB_USER',     'wordpress' );
define( 'DB_PASSWORD', 'vagrantpassword' );
define( 'DB_HOST',     'localhost' );

$table_prefix = 'test_';

defined( 'ABSPATH' ) or define( 'ABSPATH', '/vagrant/wp/' );
defined( 'WP_CONTENT_DIR' ) or define( 'WP_CONTENT_DIR', '/vagrant/content' );


