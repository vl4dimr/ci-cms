<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Navigation", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one">General settings</a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?=__("Edit a user", $this->template['module'])?></h1>



<form class="settings" action="<?=site_url('admin/member/edit')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/member/listall')?>" class="input-submit last"><?=__("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		<?php echo $this->validation->error_string; ?>
		<div id="one">
		
			<label for="username"><?=__("Username", $this->template['module'])?>: </label>
			<input name="username" type='hidden' value='<?=$member['username']?>' />
			<input id="username" type='text' value='<?=$member['username']?>' class="input-text" disabled/>
			<br />
			<label for="password"><?=__("Password", $this->template['module'])?>:</label>
			<input type="password" name="password" value="" id="password" class="input-text" /><br />

			<label for="passconf"><?=__("Confirm", $this->template['module'])?>:</label>
			<input type="password" name="passconf" value="" id="" class="input-text" /><br />			
			<label for="email"><?=__("Email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="<?=$member['email']?>" id="email" class="input-text" /><br />
			
			<label for="status"><?=__("Status", $this->template['module'])?>:</label>
				<select name="status" class="input-select" id="status">
					<option value='active' <?php if ($member['status'] == 'active'):?>selected='selected' <?php endif;?>><?=__("Active", $this->template['module'])?></option>
					<option value='disabled' <?php if ($member['status'] == 'disabled'):?>selected='selected' <?php endif;?>><?=__("Disabled", $this->template['module'])?></option>
				</select><br />

					
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->
