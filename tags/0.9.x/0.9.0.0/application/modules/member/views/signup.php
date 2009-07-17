<h1><?=__("Sign up", $this->template['module'])?></h1>



<form class="settings" id="signupform" action="<?=site_url('member/signup')?>" method="post" accept-charset="utf-8">

		<?php echo $this->validation->error_string; ?>

		<?php
		$msg = __("Please fill the form below and click on Register", $this->template['module']);
		$msg .= "<br />";
		echo $this->plugin->apply_filters('member_signup_pre_form', $msg);
		?>		
		
			<label  id="lusername" for="username"><?=__("Username", $this->template['module'])?>: </label>
			<input id="username" name="username" type='text' value='<?php echo $this->validation->username;?>' class="input-text" /><br />
			
						
			<label id="lemail" for="email"><?=__("Email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="<?php echo $this->validation->email;?>" id="email" class="input-text" /><br />

			<label id="lremail" for="remail"><?=__("Confirm email", $this->template['module'])?>:</label>
			<input type="text" name="remail" value="<?php echo $this->validation->remail;?>" id="remail" class="input-text" /><br />
			
			<?php
		
			$msg = "<p>" . __("Fields marked with * are required", $this->template['module']) . "</p>";
			echo $this->plugin->apply_filters('member_signup_post_form', $msg);
			?>		
			<input type="submit" name="submit" value="<?php echo __("Register", $this->template['module'])?>" class="input-submit" />
			<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
<script id="signup" type="text/javascript">
$(document).ready(function() {
	// validate signup form on keyup and submit
	var validator = $("#signupform").validate({
		rules: {
			username: {
				required: true,
				minlength: 4,
				remote: {
					url: "<?php echo site_url('member/ajax/exists/username') ?>",
					type: "post"
				}
			},
			email: {
				required: true,
				email: true,
				remote: {
					url: "<?php echo site_url('member/ajax/exists/email') ?>",
					type: "post"
				}
			},
			remail: {
				required: true,
				equalTo: "#email"
			}
		},
		messages: {
			username: {
				required: "<?php echo addslashes(__("Enter a username", "member")) ?>",
				minlength: jQuery.format("<?php echo addslashes(__("Enter at least {0} characters", "member")) ?>"),
				remote: jQuery.format("<?php echo addslashes(__("{0} is already in use", "member")) ?>")
			},
			email: {
				required: "<?php echo addslashes(__("Please enter a valid email address", "member")) ?>",
				minlength: "<?php echo addslashes(__("Please enter a valid email address", "member")) ?>",
				remote: jQuery.format("<?php echo addslashes(__("{0} is already in use", "member")) ?>")
			},
			remail: {
				required: "<?php echo addslashes(__("Repeat your email", "member")) ?>",
				minlength: jQuery.format("<?php echo addslashes(__("Enter at least {0} characters", "member")) ?>"),
				equalTo: "<?php echo addslashes(__("Enter the same email as above", "member")) ?>"
			}
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			error.insertAfter( element );
		},
		errorElement: "em"
	});
	

});
</script>
