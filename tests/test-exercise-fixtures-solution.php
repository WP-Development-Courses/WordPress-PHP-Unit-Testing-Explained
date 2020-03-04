<?php

class Test_Exercise_Assertions_Solution extends WP_UnitTestCase {
	/**
	 * Verify that `get_comment_count()` returns the right values if there is one approved comment.
	 */
	public function test_get_comment_count_approved() {
		self::factory()->comment->create(
			[
				'comment_approved' => 1,
			]
		);

		$count = get_comment_count();

		$this->assertEquals( 1, $count['approved'] );
		$this->assertEquals( 0, $count['awaiting_moderation'] );
		$this->assertEquals( 0, $count['post-trashed'] );
		$this->assertEquals( 0, $count['spam'] );
		$this->assertEquals( 1, $count['total_comments'] );
		$this->assertEquals( 0, $count['trash'] );
	}
}
