
<h1 id="settings"><?=__("Edit your profile", $this->template['module'])?></h1>



<form class="settings" action="<?=site_url('member/profile')?>" method="post" accept-charset="utf-8">
		<br class="clearfloat" />

		<?php echo $this->validation->error_string; ?>
			<br />
			<label for="password"><?=__("Password", $this->template['module'])?>:</label>
			<input type="password" name="password" value="" id="password" class="input-text" /><br />

			<label for="passconf"><?=__("Confirm", $this->template['module'])?>:</label>
			<input type="password" name="passconf" value="" id="" class="input-text" /><br />			
			<label for="email"><?=__("Email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="<?=$member['email']?>" id="email" class="input-text" /><br />
			

<!-- [Content] end -->
