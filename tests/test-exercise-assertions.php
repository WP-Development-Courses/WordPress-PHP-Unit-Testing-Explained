<?php


class Test_Exercise_Assertions extends WP_UnitTestCase {
	public function test_trailingslashit_adds_trailing_slash() {
		// Expected: 'http://example.com/'
		// Actual: trailingslashit( 'http://example.com' );
	}

	/**
	 * Verify that `set_post_format()` returns an `WP_Error` object when passing an invalid Post id.
	 */
	public function test_set_post_format_returns_error_for_invalid_post_id() {
		// There is no post with the id of 123456, so this function will return an error.
		$return = set_post_format( 123456, 'aside' );

		// Assert that $return contains a `WP_Error` object.

		// Assert that the error code of the `WP_Error` object (use `$return->get_error_code()`) is 'invalid_post'.

	}

	/**
	 * Verify that `register_meta()` returns `true` when the meta key was registered successfully.
	 */
	public function test_register_meta_with_post_object_type_returns_true() {
		// This function returns `true` if the meta key was registered successfully.
		$result = register_meta( 'post', 'reading_time', [] );

		// Assert that the meta key was registered successfully.

	}

	/**
	 * Verify that the video shortcode forces YouTube URLs to be HTTPS.
	 */
	public function test_wp_video_shortcode_youtube_forces_ssl() {
		// This function returns the video embed HTML.
		$html = wp_video_shortcode(
			[
				'src' => 'http://www.youtube.com/watch?v=i_cVJgIz_Cs',
			]
		);

		// Assert that the shortcode contains the HTTPS URL under the `src` attribute.
		// The HTML code to look for is `src="https://www.youtube.com/watch?v=i_cVJgIz_Cs`.

	}

	/**
	 * Verify that the video shortcode removes query arguments from Vimeo URLs.
	 */
	public function test_wp_video_shortcode_vimeo_remove_query_args() {
		// This function returns the video embed HTML.
		$html = wp_video_shortcode(
			[
				'src' => 'https://vimeo.com/190372437?foo=bar',
			]
		);

		// Assert that the shortcode does not contain the query arg.
		// This means there can be no occurrence of `?foo=bar` in the HTML.

	}
}
