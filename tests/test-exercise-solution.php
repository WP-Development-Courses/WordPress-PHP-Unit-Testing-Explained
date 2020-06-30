<?php

class Test_Exercise_Solution extends WP_UnitTestCase {
    function test_category_page_body_class() {
	    $category_id = self::factory()->category->create( array( 'name' => 'foo' ) );
	    self::factory()->post->create( array( 'category' => $category_id ) );

	    $this->go_to( home_url( "?cat=$category_id" ) );

	    $this->assertContains( "category-$category_id", get_body_class() );
    }
}
