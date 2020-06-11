<?php

class The_Byline_Test extends WP_UnitTestCase {
	public static $post_id;
	public static $user_id;

	public static function wpSetupBeforeClass() {
		self::$user_id = self::factory()->user->create( [
			'display_name' => 'John Doe',
		] );

		self::$post_id = self::factory()->post->create( [
			'post_author' => self::$user_id,
			'post_content' => '<!-- wp:paragraph -->
<p>First</p>
<!-- /wp:paragraph -->

<!-- wp:nextpage -->
<!--nextpage-->
<!-- /wp:nextpage -->

<!-- wp:paragraph -->
<p>Second</p>
<!-- /wp:paragraph -->',
		] );
	}

	function test_hide_on_paged_first_page() {
		$this->assertSame(
			'Written by John Doe',
			get_echo( 'the_byline', [ [ 'hide_on_paged' => true ] ] )
		);
	}

	function test_hide_on_paged_second_page() {
		$this->assertSame(
			'',
			get_echo( 'the_byline', [ [ 'hide_on_paged' => true ] ] )
		);
	}
}
