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
	function atest_get_comment_pages_count_no_comments() {
		// Create a comment fixture.
		$post_id = self::factory()->post->create();

		// Create a post fixture.
		 self::factory()->comment->create( array(
		 	'comment_post_ID' => $post_id,
		 ) );

		// Use `go_to()` to simulate a request to single post page.
		$this->go_to( get_permalink( $post_id ) );

		// Assert that the number of total comment pages is always 0.
		$this->assertEquals( 1, get_comment_pages_count() );
	}

	/**
	 * Ensure that `wp_trim_excerpt()` works correctly when used in secondary Loop.
	 */
	public function test_wp_trim_excerpt_secondary_loop_() {
		// Create a first post fixture with two pages.
		$post1 = self::factory()->post->create(
			array(
				'post_content' => "Post 1",
			)
		);
		// Create a second post fixture with two pages.
		$post2 = self::factory()->post->create(
			array(
				'post_content' => 'Post 2',
			)
		);

		// Use `go_to()` to simulate a request to single post page of the first post fixture ($post1).
		$this->go_to( get_permalink( $post1 ) );
		// Use `setup_postdata()` to setup the post globals for the first post fixture ($post1).
		setup_postdata( $post1 );

		// Verify that `wp_trim_excerpt()` shows the excerpt of the correct current post, i.e.
		// the post from the global post data.
		$this->assertSame( 'Post 1', wp_trim_excerpt() );

		// Create a secondary loop querying for the second post fixture ($post2).
		$query = new WP_Query(
			array(
				'post__in' => array( $post2 ),
			)
		);

		// Setup the post globals for the second post fixture ($post2).
		$query->the_post();

		// Verify that `wp_trim_excerpt()` shows the excerpt of the correct current post, i.e.
		// the post from the secondary loop.
		$this->assertSame( 'Post 2', wp_trim_excerpt() );
	}

	/**
	 * Ensure that `is_home()` returns true when the Home URL has no trailing slash.
	 */
	public function test_is_home_returns_true_for_untrailingslashed_home_url() {
		$home = untrailingslashit( get_option( 'home' ) );

		// Use `go_to()` to simulate a request to the Home URL, i.e. the `$home` variable.
		$this->go_to( $home );

		// Verify that `is_home()` returns true, even if the URL has no trailing slash.
		$this->assertTrue( is_home() );
	}

	/**
	 * Verify that `wp_get_document_title()` displays the right page number on blog archive pages.
	 */
	function test_wp_get_document_title_on_paged() {
		// Create two posts.
		self::factory()->post->create_many( 2 );
		// Display a single post per page, so that we have two pages of posts.
		update_option( 'posts_per_page', 1 );

		// Use `go_to()` to simulate a request to second page of the blog archive, i.e.
		// `http://example.org/page/2/`.
		$this->go_to( home_url( '/page/2' ) );

		// Verify that the title contains the right page number.
		$this->assertEquals(
			sprintf( '%s &#8211; Page 2 &#8211; %s',
				get_option( 'blogname'),
				get_option( 'blogdescription' )
			),
			wp_get_document_title()
		);
	}
}
