<?php

function byline_add_override_meta_box() {
	add_meta_box(
		'byline',
		'Byline',
		'byline_output_override_meta_box',
		'post'
	);
}
add_action( 'add_meta_boxes_post', 'byline_add_override_meta_box' );

function byline_output_override_meta_box( $post ) {
	?>
	<label for="byline-override">Custom byline:</label>
	<input
		type="text"
		name="byline-override"
		value="<?php echo esc_attr( get_post_meta( $post->ID, 'byline-override', true ) ); ?>"
		id="byline-override"
	/>
	<?php
}

function byline_save_override_meta_data( $post_id ) {
	if ( ! isset( $_POST['byline-override'] ) ) {
		return false;
	}

	if ( $_POST['byline-override'] === '' ) {
		if ( get_post_meta( $post_id, 'byline-override', true ) ) {
			return delete_post_meta( $post_id, 'byline-override' );
		}

		return false;
	}

	return update_post_meta(
		$post_id,
		'byline-override',
		sanitize_text_field( $_POST['byline-override'] )
	);
}
add_action( 'save_post_post', 'byline_save_override_meta_data' );
