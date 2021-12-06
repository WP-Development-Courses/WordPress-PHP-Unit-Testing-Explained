<?php

class Test_Exercise_Assertions extends WP_UnitTestCase {
	/**
	 * Verify that `get_comment_count()` returns the right values if there is one approved comment.
	 */
	public function test_get_comment_count_approved() {
		// Create an approved comment.
		// There is no need to store the id in a variable, as we are not using it in the assertion.

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
		// Create two categories.
		$category_1_id = null;
		$category_2_id = null;

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
		// Create a user with the nicename set to `maxmustermann`.
		$user_id = null;

		$users = get_users( [
			'search' => '*muster*',
			'fields' => 'ID',
		] );

		$this->assertTrue( in_array( $user_id, $users ) );
	}
}
