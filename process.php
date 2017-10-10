<?php

/*
  @package: RideHacks Custom Registration processor
  Description: Recieve POST information from user and create WP user
  Version: 2.0
  Author: Anisuzzaman Khan
  Author URI: http://aniskhan001.me
 */

require('../../../wp-load.php');

// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get fields from submitted form
    $fields = cr_get_fields();

    // Validate fields and produce errors
    if (cr_validate($fields, $errors)) {

        // If successful, register user
        $user_id = wp_insert_user($fields);

        //On success
        if ( ! is_wp_error( $user_id ) ) {
            update_user_meta($user_id, 'mailjet_subscribe_ok', 1);
            http_response_code(200);
            echo "You are now subscribed. Thank You!";
        } else {
            http_response_code(500);
            echo "Error making subscription. Please try again in a few!";
        }

        // Clear field data
        $fields = array();
    } else {
        http_response_code(403);
        echo "Email already exists!";
    }


} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}

function cr_get_fields() {
    return array(
        'user_login'    =>   isset($_POST['email'])  ?  $_POST['email']  :  '',
        'user_email'    =>   isset($_POST['email'])  ?  $_POST['email']  :  '',
        'nickname'      =>   isset($_POST['name'])   ?  $_POST['name']   :  '',
        'first_name'    =>   isset($_POST['name'])   ?  $_POST['name']   :  '',
        'display_name'  =>   isset($_POST['name'])   ?  $_POST['name']   :  '',
        'user_pass'     =>   wp_generate_password( $length=12, $include_standard_special_chars=false )
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

?>