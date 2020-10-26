<?php

class Test_Exercise_3_Solution extends WP_UnitTestCase {
	protected static $users;

	public static function wpSetupBeforeClass( $factory ) {
		self::$users = $factory->user->create_many(
			4,
			[
				'role' => 'author'
			]
		);
	}

	public function test_include_single() {
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => self::$users[0],
		] );
		$ids   = $q->get_results();

		$this->assertEquals( [ self::$users[0] ], $ids );
	}

	public function test_include_comma_separated() {
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => self::$users[0] . ', ' . self::$users[2],
		] );
		$ids   = $q->get_results();

		$this->assertEqualSets( [ self::$users[0], self::$users[2] ], $ids );
	}

	public function test_include_array() {
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => [ self::$users[0], self::$users[2] ],
		] );
		$ids   = $q->get_results();

		$this->assertEqualSets( [ self::$users[0], self::$users[2] ], $ids );
	}

	public function test_include_array_bad_values() {
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => [ self::$users[0], 'foo', self::$users[2] ],
		] );
		$ids   = $q->get_results();

		$this->assertEqualSets( [ self::$users[0], self::$users[2] ], $ids );
	}

	public function test_exclude() {
		$q = new WP_User_Query( [
			'fields'  => '',
			'exclude' => self::$users[1],
		] );

		$ids = $q->get_results();

		// Indirect test in order to ignore default user created during installation.
		$this->assertNotEmpty( $ids );
		$this->assertNotContains( self::$users[1], $ids );
	}

	public function test_get_all() {
		$users_query = new WP_User_Query( [ 'blog_id' => get_current_blog_id() ] );
		$users = $users_query->get_results();

		// +1 for the default user created during installation.
		$this->assertEquals( 5, count( $users ) );
		foreach ( $users as $user ) {
			$this->assertInstanceOf( 'WP_User', $user );
		}

		$users_query = new WP_User_Query( [ 'blog_id' => get_current_blog_id(), 'fields' => 'all_with_meta' ] );
		$users = $users_query->get_results();
		// +1 for the default user created during installation.
		$this->assertEquals( 5, count( $users ) );
		foreach ( $users as $user ) {
			$this->assertInstanceOf( 'WP_User', $user );
		}
	}

	public function test_orderby_meta_value() {
		update_user_meta( self::$users[0], 'last_name', 'Jones' );
		update_user_meta( self::$users[1], 'last_name', 'Albert' );
		update_user_meta( self::$users[2], 'last_name', 'Zorro' );
		update_user_meta( self::$users[3], 'last_name', 'Ella' );

		$q = new WP_User_Query( [
			'include'  => self::$users,
			'meta_key' => 'last_name',
			'orderby'  => 'meta_value',
			'fields'   => 'ids'
		] );

		$expected = [ self::$users[1], self::$users[3], self::$users[0], self::$users[2] ];

		$this->assertEquals( $expected, $q->get_results() );
	}

	public function test_orderby_include() {
		global $wpdb;

		$q     = new WP_User_Query( [
			'orderby' => 'include',
			'include' => [ self::$users[1], self::$users[0], self::$users[3] ],
			'fields'  => '',
		] );

		$expected_orderby = 'ORDER BY FIELD( ' . $wpdb->users . '.ID, ' . self::$users[1] . ',' . self::$users[0] . ',' . self::$users[3] . ' )';
		$this->assertContains( $expected_orderby, $q->query_orderby );

		// assertEquals() respects order but ignores type (get_results() returns numeric strings).
		$this->assertEquals( [ self::$users[1], self::$users[0], self::$users[3] ], $q->get_results() );
	}

	public function test_orderby_include_duplicate_values() {
		global $wpdb;

		$q     = new WP_User_Query( [
			'orderby' => 'include',
			'include' => [ self::$users[1], self::$users[0], self::$users[1], self::$users[3] ],
			'fields'  => '',
		] );

		$expected_orderby = 'ORDER BY FIELD( ' . $wpdb->users . '.ID, ' . self::$users[1] . ',' . self::$users[0] . ',' . self::$users[3] . ' )';
		$this->assertContains( $expected_orderby, $q->query_orderby );

		// assertEquals() respects order but ignores type (get_results() returns numeric strings).
		$this->assertEquals( [ self::$users[1], self::$users[0], self::$users[3] ], $q->get_results() );
	}
}
