<?php

class Test_Get_Byline_For_Post_Id extends WP_UnitTestCase {
    public function test_returns_author_name_for_valid_post_id() {
        $user_id = self::factory()->user->create( array(
            'display_name' => 'John Smith',
            'role' => 'author',
        ) );

        $post_id = self::factory()->post->create( array(
            'post_author' => $user_id,
        ) );

        $this->assertSame(
            'Written by John Smith',
            get_byline_for_post_id( $post_id )
        );
    }
}