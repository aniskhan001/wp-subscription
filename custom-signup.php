<?php

/*
  Plugin Name: Custom Registration
  Description: Updates user rating based on number of posts.
  Version: 1.1
  Author: Tristan Slater w/ Agbonghama Collins
  Author URI: http://kanso.ca
 */

// Other interesting way of doing this:
// https://www.sitepoint.com/handling-post-requests-the-wordpress-way/

/////////////////
// PLUGIN CORE //
/////////////////

function ridehacks_registration_function(&$fields = array(), &$errors = array()) {

  // Check args and replace if necessary
  if (!is_array($fields))     $fields = array();
  if (!is_wp_error($errors))  $errors = new WP_Error;

  // Check for form submit
  if (isset($_POST['submit'])) {

    // Get fields from submitted form
    $fields = cr_get_fields();

    // Validate fields and produce errors
    if (cr_validate($fields, $errors)) {

      // If successful, register user
      $user_id = wp_insert_user($fields);

      //On success
      if ( ! is_wp_error( $user_id ) ) {
          update_user_meta($user_id, 'mailjet_subscribe_ok', 1);
      }

      // Clear field data
      $fields = array();
    }
  }

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
  if (is_wp_error($errors) && count($errors->get_error_messages()) > 0) {

    // Display errors
    ?><ul><?php
    foreach ($errors->get_error_messages() as $key => $val) {
      ?><li>
        <?php echo $val; ?>
      </li><?php
    }
    ?></ul><?php
  }

  // Display form

  ?><div class="row">
    <div class="small-12 columns text-center">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-ridehacks.svg" alt="Ride Hacks" width="149" height="89">
      <h4 class="segment-signup-title">Never miss the latest stories from Ride Hacks</h4>
    </div>
  </div>
  <form id="signup-form" action="/" method="post" data-abide novalidate>
    <div class="row">
      <div class="small-12 columns">
        <div class="alert callout" data-abide-error style="display: none;">
          <span><i class="fi-alert"></i> Whoops, looks like there is problem with your email.</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="small-12 medium-6 columns">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" data-abide-ignore value="<?php echo (isset($fields['name']) ? $fields['name'] : '') ?>">
      </div>
      <div class="small-12 medium-6 columns">
        <label for="email">Email</label>
        <input type="email" name="email" required pattern="email" value="<?php echo (isset($fields['email']) ? $fields['email'] : '') ?>">
      </div>
    </div>
    <div class="row">
      <div class="small-12 columns">
        <input type="hidden" name="action" value="signup_form">
        <button id="submit" type="submit" name="submit" class="button expanded secondary">SUBMIT</button>
        <span id="no-spam">
          <em>We also hate SPAM and promise to always keep your email address safe.</em>
        </span>
      </div>
    </div>
    <input type="hidden" id="subscribe-referrer" name="referrer" />
    <input type="hidden" id="subscribe-cta" name="cta" />
    <input type="hidden" id="subscribe-placement" name="placement" />
    <input type="hidden" id="subscribe-content-upgrade" name="ContentUpgrade" />
  </form>
  <button class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </button><?php
}

function cr_get_fields() {
  return array(
    'user_login'    =>   isset($_POST['email'])     ?  $_POST['email']     :  '',
    'user_email'    =>   isset($_POST['email'])     ?  $_POST['email']     :  '',
    'user_pass'     =>   wp_generate_password( $length=12, $include_standard_special_chars=false ),
    'nickname'      =>   isset($_POST['name'])   ?  $_POST['name']        :  '',
    'first_name'      =>   isset($_POST['name'])   ?  $_POST['name']        :  '',
    'display_name'      =>   isset($_POST['name'])   ?  $_POST['name']        :  ''
  );
}

function cr_validate(&$fields, &$errors) {

  // Make sure there is a proper wp error obj
  // If not, make one
  if (!is_wp_error($errors))  $errors = new WP_Error;

  if (email_exists($fields['user_email'])) {
    $errors->add('email', 'Email Already in use');
  }

  // If errors were produced, fail
  if (count($errors->get_error_messages()) > 0) {
    return false;
  }

  // Else, success!
  return true;
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
