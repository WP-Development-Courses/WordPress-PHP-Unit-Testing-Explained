<?php

class Test_Byline_Override_Solution extends WP_UnitTestCase {
	/**
	 * @var int Post id.
	 */
	public static $post_id;

	/**
	 * Setup fixtures.
	 */
	public static function wpSetupBeforeClass() {
		self::$post_id = self::factory()->post->create();
	}

	/**
	 * Verify that a custom Byline in $_POST is saved to post meta.
	 */
	function test_custom_byline_is_saved() {
		$_POST['byline-override-nonce'] = wp_create_nonce( 'byline-override' );
		$_POST['byline-override'] = 'A custom byline';

		$return = byline_save_override_meta_data( self::$post_id );

		// A new field is added, so `update_post_meta()` returns an id.
		$this->assertTrue( is_numeric( $return ) );

		$this->assertSame(
			'A custom byline',
			get_post_meta( self::$post_id, 'byline-override', true )
		);
	}

	/**
	 * Verify that a custom Byline in $_POST is updates any existing Bylines in post meta.
	 */
	function test_custom_byline_updated() {
		add_post_meta( self::$post_id, 'byline-override', 'Existing Byline' );

		$_POST['byline-override-nonce'] = wp_create_nonce( 'byline-override' );
		$_POST['byline-override'] = 'New Byline';

		$return = byline_save_override_meta_data( self::$post_id );

		$this->assertTrue( $return );

		$this->assertSame(
			'New Byline',
			get_post_meta( self::$post_id, 'byline-override', true )
		);
	}

	/**
	 * Verify that an existing Byline is deleted when the user leaves the field empty.
	 */
	function test_custom_byline_is_deleted() {
		add_post_meta( self::$post_id, 'byline-override', 'Existing Byline' );

		$_POST['byline-override-nonce'] = wp_create_nonce( 'byline-override' );
		$_POST['byline-override'] = '';

		$return = byline_save_override_meta_data( self::$post_id );

		$this->assertTrue( $return );

		$this->assertSame(
			'',
			get_post_meta( self::$post_id, 'byline-override', true )
		);
	}
}
