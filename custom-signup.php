<?php

 /*

  Plugin Name: RideHacks Custom Registration
  Description: Easy email subscription to WordPress site
  Version: 2.0
  Author: Anisuzzaman Khan
  Author URI: http://aniskhan001.me

  */


/////////////////
// PLUGIN CORE //
/////////////////

function ridehacks_registration_function(&$fields = array(), &$errors = array()) {

	// Check args and replace if necessary
	if (!is_array($fields))     $fields = array();
	if (!is_wp_error($errors))  $errors = new WP_Error;

	// Santitize fields
	cr_sanitize($fields);

	// Generate form
	cr_display_form($fields, $errors);
}

function cr_sanitize(&$fields) {
	$fields['email']   =  isset($fields['email'])  ? sanitize_email($fields['email']) : '';
	$fields['name']   =  isset($fields['name'])  ? sanitize_text_field($fields['name']) : '';
}

function cr_display_form($fields = array(), $errors = null) {

	// Check for wp error obj and see if it has any errors
	if (is_wp_error($errors) && count($errors->get_error_messages()) > 0) { ?>
		<ul>
		<?php
		foreach ($errors->get_error_messages() as $key => $val) { ?>
			<li>
			<?php echo $val; ?>
			</li><?php
		}
		?>
		</ul>
	<?php
	}

	include('form.php');
}

///////////////
// SHORTCODE //
///////////////

// The callback function for the [rh_custom_shortcode] shortcode
function rh_custom_shortcode() {
	$fields = array();
	$errors = new WP_Error();

	// Buffer output
	ob_start();

	// Custom registration, go!
	ridehacks_registration_function($fields, $errors);

	// Return buffer
	return ob_get_clean();
}
add_shortcode('rh_custom_registration', 'rh_custom_shortcode');
