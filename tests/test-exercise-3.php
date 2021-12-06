<?php

class Test_Exercise_3 extends WP_UnitTestCase {
	public function test_include_single() {
		$users = $this->factory->user->create_many( 4 );
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => $users[0],
		] );
		$ids   = $q->get_results();

		$this->assertEquals( [ $users[0] ], $ids );
	}

	public function test_include_comma_separated() {
		$users = $this->factory->user->create_many( 4 );
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => $users[0] . ', ' . $users[2],
		] );
		$ids   = $q->get_results();

		$this->assertEqualSets( [ $users[0], $users[2] ], $ids );
	}

	public function test_include_array() {
		$users = $this->factory->user->create_many( 4 );
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => [ $users[0], $users[2] ],
		] );
		$ids   = $q->get_results();

		$this->assertEqualSets( [ $users[0], $users[2] ], $ids );
	}

	public function test_include_array_bad_values() {
		$users = $this->factory->user->create_many( 4 );
		$q     = new WP_User_Query( [
			'fields'  => '',
			'include' => [ $users[0], 'foo', $users[2] ],
		] );
		$ids   = $q->get_results();

		$this->assertEqualSets( [ $users[0], $users[2] ], $ids );
	}

	public function test_exclude() {
		$users = $this->factory->user->create_many(
			4,
			[
				'role' => 'author',
			]
		);

		$q = new WP_User_Query( [
			'fields'  => '',
			'exclude' => $users[1],
		] );

		$ids = $q->get_results();

		// Indirect test in order to ignore default user created during installation.
		$this->assertNotEmpty( $ids );
		$this->assertNotContains( $users[1], $ids );
	}

	public function test_get_all() {
		$users = $this->factory->user->create_many(
			4,
			[
				'role' => 'author',
			]
		);

		$users = new WP_User_Query( [ 'blog_id' => get_current_blog_id() ] );
		$users = $users->get_results();

		// +1 for the default user created during installation.
		$this->assertEquals( 5, count( $users ) );
		foreach ( $users as $user ) {
			$this->assertInstanceOf( 'WP_User', $user );
		}

		$users = new WP_User_Query( [ 'blog_id' => get_current_blog_id(), 'fields' => 'all_with_meta' ] );
		$users = $users->get_results();
		// +1 for the default user created during installation.
		$this->assertEquals( 5, count( $users ) );
		foreach ( $users as $user ) {
			$this->assertInstanceOf( 'WP_User', $user );
		}
	}

	public function test_orderby_meta_value() {
		$users = $this->factory->user->create_many(
			4,
			[
				'role' => 'author',
			]
		);

		update_user_meta( $users[0], 'last_name', 'Jones' );
		update_user_meta( $users[1], 'last_name', 'Albert' );
		update_user_meta( $users[2], 'last_name', 'Zorro' );
		update_user_meta( $users[3], 'last_name', 'Ella' );

		$q = new WP_User_Query( [
			'include'  => $users,
			'meta_key' => 'last_name',
			'orderby'  => 'meta_value',
			'fields'   => 'ids'
		] );

		$expected = [ $users[1], $users[3], $users[0], $users[2] ];

		$this->assertEquals( $expected, $q->get_results() );
	}

	public function test_orderby_include() {
		global $wpdb;

		$users = $this->factory->user->create_many( 4 );
		$q     = new WP_User_Query( [
			'orderby' => 'include',
			'include' => [ $users[1], $users[0], $users[3] ],
			'fields'  => '',
		] );

		$expected_orderby = 'ORDER BY FIELD( ' . $wpdb->users . '.ID, ' . $users[1] . ',' . $users[0] . ',' . $users[3] . ' )';
		$this->assertContains( $expected_orderby, $q->query_orderby );

		// assertEquals() respects order but ignores type (get_results() returns numeric strings).
		$this->assertEquals( [ $users[1], $users[0], $users[3] ], $q->get_results() );
	}

	public function test_orderby_include_duplicate_values() {
		global $wpdb;

		$users = $this->factory->user->create_many( 4 );
		$q     = new WP_User_Query( [
			'orderby' => 'include',
			'include' => [ $users[1], $users[0], $users[1], $users[3] ],
			'fields'  => '',
		] );

		$expected_orderby = 'ORDER BY FIELD( ' . $wpdb->users . '.ID, ' . $users[1] . ',' . $users[0] . ',' . $users[3] . ' )';
		$this->assertContains( $expected_orderby, $q->query_orderby );

		// assertEquals() respects order but ignores type (get_results() returns numeric strings).
		$this->assertEquals( [ $users[1], $users[0], $users[3] ], $q->get_results() );
	}
}
