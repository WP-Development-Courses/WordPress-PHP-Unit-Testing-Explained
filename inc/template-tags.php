<?php

function the_byline( $args = [] ) {
	global $post, $page, $multipage;

	if ( ! $post ) {
		echo '';
		return;
	}

	$parsed_args = wp_parse_args(
		$args,
		[
			'hide_on_paged' => false,
		]
	);

	if ( $parsed_args['hide_on_paged'] && $multipage && $page > 1 ) {
		echo '';
		return;
	}

	echo get_byline_for_post_id( $post->ID );
}
