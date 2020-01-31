<?php

class Test_Get_Byline extends WP_UnitTestCase {
	public function test_returns_author_name() {
		$this->assertSame(
			'Written by John Smith',
			get_byline( 'John Smith' )
		);
	}

	public function test_passing_in_array_returns_empty_string() {
		$this->assertSame(
			'',
			get_byline( [] )
		);
	}
}
