<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register the admin option page
function aignpost_admin_option_page() {
	add_menu_page(
		__( 'AIGN Post', 'aignpost' ),
        'AIGN Post',
		'manage_options',
		'aignpost-dashboard',
		'aignpost_admin_tab',
		'dashicons-admin-generic',
		20
	);

    add_submenu_page(
		'aignpost-dashboard',
		__( 'Settings', 'aignpost' ),
		__( 'Settings', 'aignpost' ),
		'manage_options',
		'aignpost-settings',
		'aignpost_settings_tab'
	);
	add_submenu_page(
		'aignpost-dashboard',
		__( 'Generate', 'aignpost' ),
		__( 'Generate Post', 'aignpost' ),
		'manage_options',
		'aignpost-generate',
		'aignpost_generate_post_tab'
	);
}

add_action( 'admin_menu', 'aignpost_admin_option_page' );
function aignpost_admin_tab() {
	?>
    <div class="wrap">
        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>


            <style>
                .plugin-instructions {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f7f7f7;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                }

                .plugin-instructions h2 {
                    font-size: 24px;
                    margin-top: 0;
                }

                .plugin-instructions p {
                    font-size: 16px;
                    line-height: 1.5;
                }

                .plugin-instructions ul {
                    margin: 10px 0;
                    padding-left: 20px;
                }

                .plugin-instructions li {
                    font-size: 16px;
                    line-height: 1.5;
                }
            </style>
            <div class="plugin-instructions">
                        <h2>Instructions for using the Plugin</h2>

                        <p>Follow the steps below to make the most of the plugin:</p>

                        <ol>
                            <li>Navigate to the <strong>Plugin Settings</strong> page from the WordPress sidebar menu.</li>
                            <li>Configure the <strong>API KEY</strong> options and settings according to your requirements.</li>
                            <li>Save the changes.</li>
                            <li>Go to the <strong>Generate Post</strong> to create post content.</li>
                            <li>Give a <strong>title</strong> and select others necessary  option then click on <strong>Publish</strong> </li>
                            <li>Check the plugin documentation for more advanced usage and customization options.</li>
                        </ol>

                        <p>If you encounter any issues or need further assistance, please consult the <a href="https://marufnahid.me" target="_blank">Plugin Documentation</a> or contact our support team.</p>
                    </div>

    </div>
	<?php
}

function aignpost_settings_tab() {
	?>
    <div class="wrap">
        <h1> <?php echo esc_html__( 'General Settings', 'aignpost' ); ?></h1>
        <div class="container-fluid margin-top-30">
            <div class="row">
                <div class="column column-50 offset-50">
                    <form method="post" action="<?php echo esc_url(admin_url( 'admin-post.php' )); ?>" novalidate="novalidate">
                        <div class="row">
                            <div class="column">
								<?php
								wp_nonce_field( 'aign_api_key_nonce', 'api_nonce' );
								?>
                                <input type="hidden" name="action" value="aignpost_api_key">
                                <label for="apikey"><?php echo esc_html__( 'API KEY', 'aignpost' ); ?></label>
                                <input name="apikey" type="text" id="apikey"
                                       aria-describedby="apikey-description"
                                       value="<?php echo esc_html( get_option( 'aignpost_apikey_value' ) ); ?>"
                                       class="regular-text"
                                       placeholder="<?php echo esc_html__( 'Enter your open ai api key.', 'aignpost' ); ?>">
                                <p class="description" id="apikey-description">
									<?php
									printf( "You will need an active <strong>API KEY</strong>
                                    for generating content for your site. If you don't have any <strong>API KEY</strong> please
                                    follow this link . <a href='https://platform.openai.com/account/api-keys'>OpenAi API KEY</a>" );
									?>
                                </p>
								<?php submit_button( 'Save Changes' ); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function aignpost_generate_post_tab() {
	$languages = [
		"Arabic"                => "Arabic",
		"Bengali"               => "Bengali",
		"Chinese (Simplified)"  => "Chinese (Simplified)",
		"Chinese (Traditional)" => "Chinese (Traditional)",
		"Dutch"                 => "Dutch",
		"Danish"                => "Danish",
		"English"               => "English",
		"French"                => "French",
		"German"                => "German",
		"Hebrew"                => "Hebrew",
		"Hindi"                 => "Hindi",
		"Indonesian"            => "Indonesian",
		"Italian"               => "Italian",
		"Japanese"              => "Japanese",
		"Korean"                => "Korean",
		"Malay"                 => "Malay",
		"Norwegian"             => "Norwegian",
		"Persian (Farsi)"       => "Persian (Farsi)",
		"Polish"                => "Polish",
		"Portuguese"            => "Portuguese",
		"Punjabi"               => "Punjabi",
		"Romanian"              => "Romanian",
		"Russian"               => "Russian",
		"Spanish"               => "Spanish",
		"Swedish"               => "Swedish",
		"Tamil"                 => "Tamil",
		"Telugu"                => "Telugu",
		"Thai"                  => "Thai",
		"Turkish"               => "Turkish",
		"Ukrainian"             => "Ukrainian",
		"Urdu"                  => "Urdu",
		"Vietnamese"            => "Vietnamese",
		"Serbian"               => "Serbian",
		"Croatian"              => "Croatian",
		"Albanian"              => "Albanian",
		"Macedonian"            => "Macedonian"
	];
	?>
    <div class="wrap">
        <h1 class=""><?php echo esc_html__( 'Generate Posts', 'aignpost' ); ?> </h1>
        <div class="container-fluid margin-top-30">
            <div class="row">
                <div class="column column-25">
                    <form action="<?php echo esc_url(admin_url( 'admin-post.php' )); ?>" method="post">
                        <fieldset>
							<?php
							wp_nonce_field( 'aign_generate_post', 'gnrt_nonce' );
							?>
                            <input type="hidden" name="action" value="aignpost_post_generate">
                            <h4><?php echo esc_html__( 'AI Assistant', 'aignpost' ); ?> </h4>
                            <div class="row">
                                <div class="column">
                                    <fieldset>
                                        <label for="topicName"><?php echo esc_html__( 'Topic', 'aignpost' ); ?> </label>
                                        <input type="text"
                                               placeholder="<?php echo esc_html__( 'WordPress', 'aignpost' ); ?> "
                                               id="topicName" name="topicName" required>
                                        <label for="topicDescription"><?php echo esc_html__( 'Topic Description', 'aignpost' ); ?> </label>
                                        <textarea
                                                placeholder="<?php echo esc_html__( 'Write a short description about WordPress.', 'aignpost' ); ?> "
                                                id="topicDescription"
                                                name="topicDescription"></textarea>
                                    </fieldset>
                                </div>
                            </div>
                            <h4><?php echo esc_html__( 'AI Settings', 'aignpost' ); ?> </h4>
                            <div class="row">
                                <div class="column column-50">
                                    <label for="creativity"><?php echo esc_html__( 'Creativity', 'aignpost' ); ?> </label>
                                    <select id="creativity" name="creativity">
                                        <option value="0.0"><?php echo esc_html__( 'None', 'aignpost' ); ?> </option>
                                        <option value="0.2"><?php echo esc_html__( 'Low', 'aignpost' ); ?> </option>
                                        <option value="0.35"><?php echo esc_html__( 'Optimal', 'aignpost' ); ?> </option>
                                        <option value="0.5"><?php echo esc_html__( 'Medium', 'aignpost' ); ?> </option>
                                        <option selected
                                                value="0.8"><?php echo esc_html__( 'High', 'aignpost' ); ?> </option>
                                        <option value="1"><?php echo esc_html__( 'Max', 'aignpost' ); ?> </option>
                                    </select>
                                </div>
                                <div class="column column-50">
                                    <label for="character"><?php echo esc_html__( 'Character', 'aignpost' ); ?> </label>
                                    <input type="number" name="character" id="character"
                                           value="<?php echo esc_attr__( 200, 'aignpost' ) ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="column column-50">
                                    <label for="tone_of_voice"><?php echo esc_html__( 'Tone of voice', 'aignpost' ); ?> </label>
                                    <select id="tone_of_voice" name="tone_of_voice">
                                        <option value="formal"><?php echo esc_html__( 'Formal', 'aignpost' ); ?> </option>
                                        <option value="informative"
                                                selected><?php echo esc_html__( 'Informative', 'aignpost' ); ?> </option>
                                        <option value="friendly"><?php echo esc_html__( 'Friendly', 'aignpost' ); ?> </option>
                                        <option value="playful"><?php echo esc_html__( 'Playful', 'aignpost' ); ?> </option>
                                        <option value="authoritative"><?php echo esc_html__( 'Authoritative', 'aignpost' ); ?> </option>
                                        <option value="empathetic"><?php echo esc_html__( 'Empathetic', 'aignpost' ); ?> </option>
                                    </select>
                                </div>
                                <div class="column column-50">
                                    <label for="repetition"><?php echo esc_html__( 'Repetition', 'aignpost' ); ?> </label>
                                    <select id="repetition" name="repetition">
                                        <option value="0.0"
                                                selected><?php echo esc_html__( 'None', 'aignpost' ); ?> </option>
                                        <option value="0.2"><?php echo esc_html__( 'Low', 'aignpost' ); ?> </option>
                                        <option value="0.35"><?php echo esc_html__( 'Optimal', 'aignpost' ); ?> </option>
                                        <option value="0.5"><?php echo esc_html__( 'Medium', 'aignpost' ); ?> </option>
                                        <option value="0.8"><?php echo esc_html__( 'High', 'aignpost' ); ?> </option>
                                        <option value="1"><?php echo esc_html__( 'Max', 'aignpost' ); ?> </option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="language"><?php echo esc_html__( 'Language', 'aignpost' ); ?> </label>
                                <select name="language" id="language">
									<?php
									foreach ( $languages as $key => $val ) {
										?>
                                        <option value="<?php echo esc_attr( $key ) ?>">
											<?php echo esc_html__( $val, 'aignpost' ); ?>
                                        </option>
									<?php } ?>
                                </select>
                            </div>
							<?php submit_button( 'Generate' ); ?>
                        </fieldset>
                    </form>
                </div>
                <div class="column column-75">
                    <form action="<?php echo esc_url(admin_url( 'admin-post.php' )); ?>" method="post">
                        <div class="row">
                            <div class="column column-75">

								<?php
								wp_nonce_field( 'aignpost_insert_post_nonce', 'nonce' );
								?>
                                <input type="hidden" name="action" value="aignpost_insert">
                                <label for="aign_post_title"><?php echo esc_html__( 'Post Title', 'aignpost' ); ?></label>
                                <input type="text" name="post_title" id="aign_post_title"
                                       placeholder=" <?php echo esc_html__( 'Write a title for this post.', 'aignpost' ); ?>"
                                       required>
                                <label for="aignpost_post_text"><?php echo esc_html__( 'Post Content', 'aignpost' ); ?></label>
								<?php
								$content            = "";
								$custom_editor_id   = "aignpost_post_text";
								$custom_editor_name = "aignpost_post_text";
								$args               = array(
									'media_buttons' => false,
									'textarea_name' => $custom_editor_name,
									'textarea_rows' => 40,
									'quicktags'     => false,
								);
								wp_editor( $content, $custom_editor_id, $args );
								?>
								<?php
								submit_button( "Publish" );
								?>
                            </div>
                            <div class="column column-25">
                                <div class="row">
                                    <div class="column">
                                        <label for="post_status"><?php echo esc_html__( 'Post Status', 'aignpost' ); ?></label>
                                        <select id="post_status" name="post_status">
                                            <option value="<?php echo esc_attr__( 'publish', 'aignpost' ); ?>"
                                                    selected><?php echo esc_html__( 'Publish', 'aignpost' ); ?></option>
                                            <option value="<?php echo esc_attr__( 'pending', 'aignpost' ); ?>"><?php echo esc_html__( 'Pending', 'aignpost' ); ?></option>
                                            <option value="<?php echo esc_attr__( 'draft', 'aignpost' ); ?>"><?php echo esc_html__( 'Draft', 'aignpost' ); ?></option>
                                            <option value="<?php echo esc_attr__( 'future', 'aignpost' ); ?>"><?php echo esc_html__( 'Future', 'aignpost' ); ?></option>
                                            <option value="<?php echo esc_attr__( 'private', 'aignpost' ); ?>"><?php echo esc_html__( 'Private', 'aignpost' ); ?></option>
                                        </select>
										<?php
										$args       = array(
											'public' => true,
										);
										$post_types = get_post_types( $args, 'objects' );
										?>
                                        <label for="post_type"><?php echo esc_html__( 'Post Type', 'aignpost' ); ?></label>
                                        <select name="post_type">
											<?php foreach ( $post_types as $post_type_obj ):
												$labels = get_post_type_labels( $post_type_obj );
												?>
                                                <option value="<?php echo esc_attr( $post_type_obj->name ); ?>"><?php echo esc_html( $labels->name ); ?></option>
											<?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php
}

