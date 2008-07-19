<!-- [Content] start -->
<div class="content wide">

<h1 id="login"><?=__("Login")?></h1>
<?php
$var = "login_intro_" . $this->session->userdata('lang'); 
if (isset($this->system->$var)) 
{
	echo $this->system->$var;
}
?>
<form class="login" action="<?=site_url('member/login')?>" method="post" accept-charset="utf-8">
	<fieldset>
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		<p>
		<?php
		$msg = __("Please enter here your login and password");
		echo $this->plugin->apply_filters('login_pre_form', $msg);
		?>
		</p>
		<label for="username"><?=__("Username")?>:</label>
		<input type='text' name='username' id='username' class="input-text" /><br />
		<label for="password"><?=__("Password")?>:</label>
		<input type="password" name="password" value="" id="password" class="input-text" />
		<br class="clearfloat" />
		<p><input type="submit" name="submit" value="Login &raquo;" id="submit" class="input-submit" /></p>
	</fieldset>
	<?php

		if (isset($this->system->login_signup_enabled) && $this->system->login_signup_enabled == 1)
		{
			echo "<p><a href='" . site_url('member/signup') . "'>" . __("Sign up") . "</a></p>";
		}
		echo "<p><a href='" . site_url('member/lostpass') . "'>" . __("Lost password ?") . "</a></p>";
	?>
</form>

</div>
<!-- [Content] end -->
