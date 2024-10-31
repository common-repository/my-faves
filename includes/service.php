<?php

// Handle the ajax request

add_action( 'wp_ajax_myfavesadd', 'myfaves_add_handler' );
function myfaves_add_handler() {
   
    	$v=check_ajax_referer( 'MyFaves' );
		if ($v) {

			$myfaveslink 				= isset( $_POST[ 'myfavesurl' ] )			? 	esc_url_raw( trim ( $_POST[ 'myfavesurl' ] )): '';
			$myfavestitle				= isset( $_POST[ 'myfavestitle' ] )			? 	sanitize_text_field(trim ( $_POST[ 'myfavestitle' ] )): '';
			$myfavespid					= isset( $_POST[ 'myfavespid' ] )			?  	intval(sanitize_text_field(trim( $_POST[ 'myfavespid' ] ))): '';
			// $myfavestags				= isset( $_POST[ 'myfavestags' ] )			?  	sanitize_text_field(trim( $_POST[ 'myfavestags' ] )): '';

	    	$x=myfaves_add($myfaveslink, $myfavestitle, $myfavespid);

			if ($x) { 
				$response="Faved";
			} else {
				$response="Err ".$title;
			}
		} else {
				$response = "Error!";
		}
	wp_send_json($response) ; 
}

add_action( 'wp_ajax_myfavesremove', 'myfaves_remove_handler' );
function myfaves_remove_handler() {

	 	$v=check_ajax_referer( 'MyFaves' );
		if ($v) {
			$myfavespid				= isset( $_POST[ 'myfavespid' ] )			?  	intval(sanitize_text_field(trim( $_POST[ 'myfavespid' ] ))): '';

			$x= myfaves_remove($myfavespid);

			if ($x) {
				$response="Unfaved";
			} else {
				$response="Err";
			}
		} else {
			$response = "Error!";
		}
	wp_send_json($response) ; 
}

add_action( 'wp_ajax_myfavesaddtags', 'myfaves_addtags_handler' );
function myfaves_addtags_handler() {

		$v=check_ajax_referer( 'MyFaves' );
		if ($v) {
			$myfavespid					= isset( $_POST[ 'myfavespid' ] )			?  	intval(sanitize_text_field(trim( $_POST[ 'myfavespid' ] ))): '';
			$myfavestags				= isset( $_POST[ 'myfavestags' ] )			?  	sanitize_text_field(trim( $_POST[ 'myfavestags' ] )): '';

			$x= myfaves_add_favetags($myfavespid,$myfavestags);

			if ($x) { 
				$response="Faved";
			} else {
				$response="Err";
			}
		} else {
			$response = "Error!";
		}
	wp_send_json($response) ; 
}

 



  


