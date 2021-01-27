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
	if ( ! is_string( $author_name ) ) {
		return '';
	}

	return 'Written by ' . $author_name;
}
