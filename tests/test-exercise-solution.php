<?php

class Test_Exercise_Solution extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();

		// Enable pretty permalinks.
		$this->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );
		// See https://core.trac.wordpress.org/ticket/35452
		create_initial_taxonomies();
	}

	function test_category_page_body_class() {
		$this->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );

	    $category_id = self::factory()->category->create( array( 'name' => 'foo' ) );
	    self::factory()->post->create( array( 'category' => $category_id ) );

	    $this->go_to( home_url( '/category/foo/' ) );

	    $this->assertContains( "category-$category_id", get_body_class() );
    }

	/**
	 * Validate `get_comments_pages_count()` for posts with no comments.
	 */
	function test_get_comments_no_comments() {
		$post_id = self::factory()->post->create(
			array(
				'post_title' => 'comment--post',
				'post_type'  => 'post',
			)
		);

		$this->go_to( get_permalink( $post_id ) );

		global $wp_query;
		unset( $wp_query->comments );

		$comments = get_comments( array( 'post_id' => $post_id ) );

		$this->assertEquals( 0, get_comment_pages_count( $comments, 10, false ) );
		$this->assertEquals( 0, get_comment_pages_count( $comments, 1, false ) );
		$this->assertEquals( 0, get_comment_pages_count( $comments, 0, false ) );
		$this->assertEquals( 0, get_comment_pages_count( $comments, 10, true ) );
		$this->assertEquals( 0, get_comment_pages_count( $comments, 5 ) );
		$this->assertEquals( 0, get_comment_pages_count( $comments ) );
		$this->assertequals( 0, get_comment_pages_count( null, 1 ) );
	}
}
