<?php
class Test_Get_Byline_For_Content_Ids extends WP_UnitTestCase {
    public function test_get_byline_for_user_id() {
        $user_id = self::factory()->user->create( [
			'display_name' => 'John Smith',
		] );

        $this->assertSame(
            'Written by John Smith',
            get_byline_for_user_id( $user_id )
        );
    }

    public function test_get_byline_for_post_id() {
        $user_id = self::factory()->user->create( [
			'display_name' => 'John Smith',
		] );

        $post_id = self::factory()->post->create( [
            'post_author' => $user_id,
        ] );

        $this->assertSame(
            'Written by John Smith',
            get_byline_for_post_id( $post_id )
        );
    }
}
