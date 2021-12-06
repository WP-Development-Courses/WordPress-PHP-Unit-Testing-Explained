<?php

class Test_Exercise_1 extends WP_UnitTestCase {
	protected $post_id;

	public function setUp() {
		$this->post_id = self::factory()->post->create();
	}

	public function test_get_instance_should_work_for_numeric_string() {
		$found = WP_Post::get_instance( (string) $this->post_id );

		$this->assertSame( $this->post_id, $found->ID );
	}

	public function test_get_instance_should_fail_for_negative_number() {
		$found = WP_Post::get_instance( -$this->post_id );

		$this->assertFalse( $found );
	}

	public function test_get_instance_should_succeed_for_float_that_is_equal_to_post_id() {
		$found = WP_Post::get_instance( (float) $this->post_id );

		$this->assertSame( $this->post_id, $found->ID );
	}
}

