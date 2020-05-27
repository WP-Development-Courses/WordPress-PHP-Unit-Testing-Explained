<?php

class Test_Byline_Override extends WP_UnitTestCase {
	public static $post_id;

	public static $user_id;

	public static function wpSetupBeforeClass() {
		self::$user_id = self::factory()->user->create( [
			'role' => 'author',
		] );

		self::$post_id = self::factory()->post->create( [
			'post_author' => self::$user_id,
		] );
	}

	function test_custom_byline_is_saved() {
		wp_set_current_user( self::$user_id );
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

	function test_custom_byline_updated() {
		wp_set_current_user( self::$user_id );
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

	function test_custom_byline_is_deleted() {
		wp_set_current_user( self::$user_id );
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
