
<h1 id="settings"><?=__("Edit your profile", $this->template['module'])?></h1>



<form class="settings" action="<?=site_url('member/profile')?>" method="post" accept-charset="utf-8">
		<br class="clearfloat" />

			<?php echo $this->validation->error_string; ?>
			<br />
			
			<?php 
			
			$msg = __("Please fill here your profile. Do not touch those you don't want to change.", $this->template['module']);
			echo $this->plugin->apply_filters('member_profile_pre_form', $msg);
			?>
			<br />
			<label for="password"><?=__("Password", $this->template['module'])?>:</label>
			<input type="password" name="password" value="" id="password" class="input-text" /><br />

			<label for="passconf"><?=__("Confirm", $this->template['module'])?>:</label>
			<input type="password" name="passconf" value="" id="" class="input-text" /><br />			
			<label for="email"><?=__("Email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="<?php echo $member['email']?>" id="email" class="input-text" /><br />
			
			<?php 
			
			$msg = "";
			echo $this->plugin->apply_filters('member_profile_post_form', $msg);
			?>
			<input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" />
			<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
