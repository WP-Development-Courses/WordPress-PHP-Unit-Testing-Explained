<?php
/*
Plugin Name: Byline
Version: 1.0.0
*/

/**
 * Output the post byline.
 *
 * @param string $author_name Name of the post author.
 *
 * @return string Post byline.
 */
function get_byline( $author_name ) {
	return 'Written by ' . $author_name;
}
