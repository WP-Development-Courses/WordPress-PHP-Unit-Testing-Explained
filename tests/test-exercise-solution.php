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

	/**
	 * Ensure that `wp_trim_excerpt()` works correctly when used in secondary Loop.
	 */
	public function test_wp_trim_excerpt_secondary_loop_respect_more() {
		$post1 = self::factory()->post->create(
			array(
				'post_content' => 'Post 1 Page 1<!--more-->Post 1 Page 2',
			)
		);
		$post2 = self::factory()->post->create(
			array(
				'post_content' => 'Post 2 Page 1<!--more-->Post 2 Page 2',
			)
		);

		$this->go_to( get_permalink( $post1 ) );
		setup_postdata( get_post( $post1 ) );

		$q = new WP_Query(
			array(
				'post__in' => array( $post2 ),
			)
		);
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$this->assertSame( 'Post 2 Page 1', wp_trim_excerpt() );
			}
		}
	}
}
