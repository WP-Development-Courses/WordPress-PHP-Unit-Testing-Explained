<?php

class Test_Byline_Override extends WP_UnitTestCase {
	public static $post_id;

	public static function wpSetupBeforeClass() {
		self::$post_id = self::factory()->post->create();
	}

	function test_custom_byline_is_saved() {
		$_POST['byline-override'] = $this->byline_override;

		byline_save_override_meta_data( self::$post_id );

		$this->assertSame(
			$this->byline_override,
			get_post_meta( self::$post_id, 'byline-override', true )
		);
	}
}
