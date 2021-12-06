<?php

class Test_Exercise_1_Solution extends WP_UnitTestCase {
	protected static $post_id;

	public static function wpSetUpBeforeClass() {
		self::$post_id = self::factory()->post->create();
	}

	public function test_get_instance_should_work_for_numeric_string() {
		$found = WP_Post::get_instance( (string) self::$post_id );

		$this->assertSame( self::$post_id, $found->ID );
	}

	public function test_get_instance_should_fail_for_negative_number() {
		$found = WP_Post::get_instance( -self::$post_id );

		$this->assertFalse( $found );
	}

	public function test_get_instance_should_succeed_for_float_that_is_equal_to_post_id() {
		$found = WP_Post::get_instance( (float) self::$post_id );

		$this->assertSame( self::$post_id, $found->ID );
	}
}

