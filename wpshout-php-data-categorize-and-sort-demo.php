<?php

/*
Plugin Name: WPShout PHP Data Categorize and Sort Demo
Description: Sorts and categorizes data
Author: WPShout
Author URI: http://wpshout.com/
*/

add_shortcode( 'abc_posts_by_comment_status', 'wpshout_display_alphabetized_posts_by_comment_status' );
function wpshout_display_alphabetized_posts_by_comment_status( $content ) {

	$args = array( 'posts_per_page' => -1, 'post_type' => 'any' );
	$posts = get_posts( $args );

	$by_comment = array();
	foreach( $posts as $post ) {
		$by_comment[ $post->comment_status ][] = $post;
	}

	$closed = wpshout_return_sorted_list( $by_comment['closed'], 'Comments Closed:' );
	$open = wpshout_return_sorted_list( $by_comment['open'], 'Comments Open:' );

	$return_str = '';
	if( $closed ) :
		$return_str .= $closed;
	endif;

	if( $open ) :
		$return_str .= $open;
	endif;

	return $return_str;
}

function wpshout_return_sorted_list( $array, $section_title ) {
	if( empty( $array ) || ! is_array( $array ) ) :
		return false;
	endif;
	$return_str = '<h3>' . $section_title . '</h3>';
	
	$by_post_type = array();
	foreach( $array as $post ) {
		$by_post_type[ $post->post_type ][] = $post;
	}

	foreach( $by_post_type as $type => $posts ) {
		$return_str .= '<h4>Posts of Type ' . ucfirst ( $type ) . ':</h4>';
		usort( $posts, 'wpshout_reorder_query_by_post_title_alphabetical' );
		foreach( $posts as $post ) {
			$return_str .= $post->post_title . '<br>';
		}
	}

	return $return_str;
}

/* http://kuttler.eu/code/order-posts-in-a-wp_query-manually/ */
function wpshout_reorder_query_by_post_title_alphabetical( $a, $b ) {
	$a_title = $a->post_title;
	$b_title = $b->post_title;
    return ( strcmp($a_title, $b_title ) > 0 ) ? 1 : -1;
}