<?php

class Test_Get_Byline_For_User_Id extends WP_UnitTestCase {
    public function test_returns_author_name_for_valid_user_id() {
        $user_id = self::factory()->user->create( array(
            'display_name' => 'John Smith',
            'role' => 'author',
        ) );

        $this->assertSame(
            'Written by John Smith',
            get_byline_for_user_id( $user_id )
        );
    }
}