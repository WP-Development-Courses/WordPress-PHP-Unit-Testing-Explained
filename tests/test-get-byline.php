<?php

class Test_Get_Byline extends WP_UnitTestCase {
	public function test_returns_author_name() {
		$this->assertSame(
			'Written by John Smith',
			get_byline( 'John Smith' )
		);
	}

	public function test_returns_empty_string_with_invalid_argument() {
		$this->assertSame(
			'',
			get_byline( array() )
		);
	}
}
