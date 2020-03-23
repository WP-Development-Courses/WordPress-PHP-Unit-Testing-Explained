<?php

class Test_Exercise_Assertions_Solution extends WP_UnitTestCase {
	/**
	 * Verify that `get_comment_count()` returns the right values if there is one approved comment.
	 */
	public function test_get_comment_count_approved() {
		self::factory()->comment->create();

		$count = get_comment_count();

		$this->assertEquals( 1, $count['approved'] );
		$this->assertEquals( 0, $count['awaiting_moderation'] );
		$this->assertEquals( 0, $count['post-trashed'] );
		$this->assertEquals( 0, $count['spam'] );
		$this->assertEquals( 1, $count['total_comments'] );
		$this->assertEquals( 0, $count['trash'] );
	}

	/**
	 * Verify that `wp_list_categories()` handles highlighting the current category correctly.
	 */
	public function test_wp_list_categories_class_containing_current_cat() {
		$category_1_id = self::factory()->category->create();
		$category_2_id = self::factory()->category->create();

		$category_list = wp_list_categories( [
			'hide_empty'       => false,
			'echo'             => false,
			'current_category' => $category_2_id,
		] );

		$this->assertNotRegExp( '/class="[^"]*cat-item-' . $category_1_id . '[^"]*current-cat[^"]*"/', $category_list );
		$this->assertRegExp( '/class="[^"]*cat-item-' . $category_2_id . '[^"]*current-cat[^"]*"/', $category_list );
	}

	/**
	 * Verify that searching for a part of a user's nicename returns the user.
	 */
	public function test_search_users_nicename() {
		$user_id = self::factory()->user->create( [
			'user_nicename' => 'maxmustermann',
		] );

		$users = get_users( [
			'search' => '*muster*',
			'fields' => 'ID',
		] );

		$this->assertTrue( in_array( $user_id, $users ) );
	}
}
