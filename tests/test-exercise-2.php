<?php

class Test_Exercise_2 extends WP_UnitTestCase {
	public $post_id;
	public $comment_id;

	function setUp() {
		parent::setUp();

		$this->post_id    = self::factory()->post->create();
		$this->comment_id = self::factory()->comment->create(
			array(
				'comment_post_ID'      => $this->post_id,
				'comment_approved'     => '1',
				'comment_author'       => 'Bob',
				'comment_author_email' => 'bobthebuilder@example.com',
				'comment_author_url'   => 'http://example.com',
				'comment_content'      => 'Yes, we can!',
			)
		);

		update_option( 'comment_previously_approved', 0 );
	}

	function tearDown() {
		update_option( 'comment_previously_approved', 1 );
	}

	public function test_allow_comment_if_comment_author_emails_differ() {
		$now          = time();
		$comment_data = array(
			'comment_post_ID'      => $this->post_id,
			'comment_author'       => 'Bob',
			'comment_author_email' => 'sideshowbob@example.com',
			'comment_author_url'   => 'http://example.com',
			'comment_content'      => 'Yes, we can!',
			'comment_author_IP'    => '192.168.0.1',
			'comment_parent'       => 0,
			'comment_date_gmt'     => gmdate( 'Y-m-d H:i:s', $now ),
			'comment_agent'        => 'Bobbot/2.1',
			'comment_type'         => '',
		);

		$this->assertSame(
			1,
			wp_allow_comment( $comment_data )
		);
	}

	public function test_die_as_duplicate_if_comment_author_name_and_emails_match() {
		$this->expectException( 'WPDieException' );

		$now          = time();
		$comment_data = array(
			'comment_post_ID'      => $this->post_id,
			'comment_author'       => 'Bob',
			'comment_author_email' => 'bobthebuilder@example.com',
			'comment_author_url'   => 'http://example.com',
			'comment_content'      => 'Yes, we can!',
			'comment_author_IP'    => '192.168.0.1',
			'comment_parent'       => 0,
			'comment_date_gmt'     => gmdate( 'Y-m-d H:i:s', $now ),
			'comment_agent'        => 'Bobbot/2.1',
			'comment_type'         => '',
		);

		wp_allow_comment( $comment_data );
	}
}
