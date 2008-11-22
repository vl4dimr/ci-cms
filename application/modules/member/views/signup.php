<h1><?=__("Sign up", $this->template['module'])?></h1>



<form class="settings" action="<?=site_url('member/signup')?>" method="post" accept-charset="utf-8">

		<?php echo $this->validation->error_string; ?>

		<?php
		$msg = __("Please fill the form below and click on Register", $this->template['module']);
		$msg .= "<br />";
		echo $this->plugin->apply_filters('member_signup_pre_form', $msg);
		?>		
		
			<label for="username"><?=__("Username", $this->template['module'])?>: </label>
			<input id="username" name="username" type='text' value='<?php echo $this->validation->username;?>' class="input-text" /><br />
			
						
			<label for="email"><?=__("Email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="<?php echo $this->validation->email;?>" id="email" class="input-text" /><br />

			<label for="remail"><?=__("Confirm email", $this->template['module'])?>:</label>
			<input type="text" name="remail" value="<?php echo $this->validation->remail;?>" id="email" class="input-text" /><br />
			
			<?php
		
			$msg = "<p>" . __("Fields marked with * are required", $this->template['module']) . "</p>";
			echo $this->plugin->apply_filters('member_signup_post_form', $msg);
			?>		
			<input type="submit" name="submit" value="<?php echo __("Register", $this->template['module'])?>" class="input-submit" />
			<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
