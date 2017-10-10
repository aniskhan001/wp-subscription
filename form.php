<?php

/*
  @package: Subscription Form
  Description: Display a form for user
  Version: 2.0
 */

?>

<style>
	#form-messages {
		padding: 5px 20px;
		margin-top: 10px;
	}

	#form-messages.success {
		background-color: #52eb89;
	}

	#form-messages.error {
		background-color: #fd9898;
	}
</style>

<div class="row">
    <div class="small-12 columns text-center">
		<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-ridehacks.svg" alt="Ride Hacks" width="149" height="89">
		<h4 class="segment-signup-title">Never miss the latest stories from Ride Hacks</h4>
    </div>
</div>

<form id="signup-form" action="<?php echo plugin_dir_url( __FILE__ ); ?>process.php" method="post" data-abide novalidate>
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

<div id="form-messages"></div>

<button class="close-button" data-close aria-label="Close modal" type="button">
	<span aria-hidden="true">&times;</span>
</button>

<script src="<?php echo plugin_dir_url( __FILE__ ); ?>send.js"></script>
