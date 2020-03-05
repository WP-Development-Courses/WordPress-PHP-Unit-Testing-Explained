<?php

class Test_Using_Factory extends WP_UnitTestCase {
    function test_has_excerpt() {
	    $post_id = self::factory()->post->create();

	    $this->assertTrue( has_excerpt( $post_id ) );
    }
}
