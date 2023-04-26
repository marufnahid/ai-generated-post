<?php

require PLUGIN_DIR_PATH . 'vendor/autoload.php';

use Orhanerday\OpenAi\OpenAi;


function aignpost_api_key_handler() {
	$nonce = sanitize_text_field( $_POST['api_nonce'] );

	if ( wp_verify_nonce( $nonce, 'aign_api_key_nonce' ) ) {
		$apikey = sanitize_text_field( $_POST['apikey'] );
		update_option( "aignpost_apikey_value", $apikey );
		wp_redirect( "admin.php?page=aignpost-generate" );
	}

}

add_action( 'admin_post_aignpost_api_key', 'aignpost_api_key_handler' );

function aignpost_generator() {

	$nonce = sanitize_text_field( $_POST['gnrt_nonce'] );
	if ( wp_verify_nonce( $nonce, 'aign_generate_post' ) ) {

		$apikey = get_option( 'aignpost_apikey_value' );

		$open_ai = new OpenAi( $apikey );

		$topicName        = sanitize_text_field( $_POST['topicName'] );
		$topicDescription = sanitize_text_field( $_POST['topicDescription'] );
		$creativity       = sanitize_text_field( $_POST['creativity'] );
		$character        = sanitize_text_field( $_POST['character'] );
		$tone_of_voice    = sanitize_text_field( $_POST['tone_of_voice'] );
		$repetition       = sanitize_text_field( $_POST['repetition'] );
		$language         = sanitize_text_field( $_POST['language'] );

		$topicName        = $topicName ?? "";
		$topicDescription = $topicDescription ?? "";
		$creativity       = $creativity ?? 1;
		$character        = $character ?? 256;
		$tone_of_voice    = $tone_of_voice ?? "formal";
		$repetition       = $repetition ?? 0;
		$language         = $language ?? "english";
		$model            = 'text-davinci-003';
		$title_prompt     = sprintf( "Write a short awesome title for %s ", $topicName );
		$body_prompt      = sprintf( "Write about %s %s.%s and writing language is %s.", $topicName, $tone_of_voice, $topicDescription, $language );


		if ( "" != $topicName ) {
			$aign_complete_title = $open_ai->completion( [
				'model'             => $model,
				'prompt'            => $title_prompt,
				'temperature'       => 1,
				'max_tokens'        => 15,
				'frequency_penalty' => 0,
			] );

			$aign_complete_body = $open_ai->completion( [
				'model'             => $model,
				'prompt'            => $body_prompt,
				'temperature'       => intval( $creativity ),
				'max_tokens'        => intval( $character ),
				'frequency_penalty' => intval( $repetition ),
			] );


			if ( "" != $aign_complete_body && "" != $aign_complete_title ) {
				set_transient( 'aignpost_complete_title', $aign_complete_title );
				set_transient( 'aignpost_complete_body', $aign_complete_body );
			}


		}
		wp_redirect( "admin.php?page=aignpost-generate" );
	}
}

add_action( 'admin_post_aignpost_post_generate', 'aignpost_generator' );

function aignpost_localized_data() {

	$_title = get_transient( 'aignpost_complete_title' ) ?? "";
	$_body  = get_transient( 'aignpost_complete_body' ) ?? "";

	$title = json_decode( $_title );
	$body  = json_decode( $_body );

	if ( ( is_object( $title ) && property_exists( $title, "choices" ) ) || ( is_object( $body ) && property_exists( $body, "choices" ) ) ) {
		$title_data = $title->choices[0]->text;
		$body_data  = $body->choices[0]->text;
		$data       = array(
			'titleContent' => $title_data,
			'bodyContent'  => $body_data,
			'ajaxurl'      => admin_url( 'admin-ajax.php' )
		);
	} else {
		$data = array(
			'titleContent' => "",
			'bodyContent'  => "",
			'ajaxurl'      => admin_url( 'admin-ajax.php' )
		);
	}
	wp_localize_script( 'aignpost-main', 'aignpost_generated_data', $data );
}

add_action( 'admin_enqueue_scripts', 'aignpost_localized_data' );
function aignpost_insert_post() {

	$nonce = sanitize_text_field( $_POST['nonce'] );

	if ( wp_verify_nonce( $nonce, 'aignpost_insert_post_nonce' ) ) {

		$post_title   = sanitize_text_field( $_POST['post_title'] );
		$post_status  = sanitize_text_field( $_POST['post_status'] );
		$post_type    = sanitize_text_field( $_POST['post_type'] );
		$post_content = wp_kses_post( $_POST['aignpost_post_text'] );

		$post_title   = $post_title ?? "";
		$post_content = $post_content ?? "";
		$post_status  = $post_status ?? "publish";
		$post_type    = $post_type ?? "post";
		$author       = get_current_user_id();
		$post_date    = date( 'Y-m-d H:i:s' );


		if ( "" != $post_content && "" != $post_title ) {
			$aign_post = array(
				'post_title'   => $post_title,
				'post_content' => $post_content,
				'post_status'  => $post_status,
				'post_author'  => $author,
				'post_type'    => $post_type,
				'post_date'    => $post_date
			);

			$post_id = wp_insert_post( $aign_post );

			if ( ! is_wp_error( $post_id ) ) {
				delete_transient( 'aignpost_complete_title' );
				delete_transient( 'aignpost_complete_body' );
				wp_redirect( "admin.php?page=aignpost-generate" );
			} else {
				echo $post_id->get_error_message();
			}
		}
	}
	wp_redirect( "admin.php?page=aignpost-generate" );
	?>
	<?php
}

add_action( 'admin_post_aignpost_insert', 'aignpost_insert_post' );



