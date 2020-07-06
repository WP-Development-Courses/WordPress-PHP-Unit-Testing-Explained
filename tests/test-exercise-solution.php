<?php

class Test_Exercise_Solution extends WP_UnitTestCase {
	function setUp() {
		parent::setUp();

		// Enable pretty permalinks.
		$this->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );
		// See https://core.trac.wordpress.org/ticket/35452
		create_initial_taxonomies();
	}

	/**
	 * Verify that `get_body_class()` contains `category-{$id}` on category archive pages.
	 */
	function test_category_page_body_class() {
		$this->set_permalink_structure( '/%year%/%monthnum%/%day%/%postname%/' );

		// Create a category fixture.
	    $category_id = self::factory()->category->create( array( 'name' => 'foo' ) );
	    // Create a post fixture with the category fixture assigned.
	    self::factory()->post->create( array( 'category' => $category_id ) );

	    // Use `go_to()` to simulate a request to the `foo` category archive page, i.e.
		// `http://example.org/category/foo/`.
	    $this->go_to( get_term_link( $category_id, 'category' ) );

	    // Assert that `get_body_class()` contains `category-{$id}`.
	    $this->assertContains( "category-$category_id", get_body_class() );
    }

	/**
	 * Validate `get_comments_pages_count()` for posts with no comments.
	 */
	function test_get_comment_pages_count_no_comments() {
		// Create a post fixture.
		$post_id = self::factory()->post->create();

		// Use `go_to()` to simulate a request to single post page.
		$this->go_to( get_permalink( $post_id ) );

		// Get comments.
		$comments = get_comments( array( 'post_id' => $post_id ) );

		// Assert that the number of total comment pages is always 0.
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

	/**
	 * Ensure that `is_home()` returns true when the Home URL has no trailing slash.
	 */
	public function test_go_to_should_go_to_home_page_when_passing_the_untrailingslashed_home_url() {
		$this->assertFalse( is_home() );
		$home = untrailingslashit( get_option( 'home' ) );

		$this->go_to( $home );

		$this->assertTrue( is_home() );
	}

	/**
	 * Verify that `wp_get_document_title()` works on blog archive pages.
	 */
	function test_wp_get_document_title_on_paged() {
		self::factory()->post->create_many( 2 );
		update_option( 'posts_per_page', 1 );

		$this->go_to( home_url( '/page/2' ) );

		$this->assertEquals(
			sprintf( '%s &#8211; Page 2 &#8211; %s',
				get_option( 'blogname'),
				get_option( 'blogdescription' )
			),
			wp_get_document_title()
		);
	}
}
