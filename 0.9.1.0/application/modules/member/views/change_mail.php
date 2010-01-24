<h1 id="settings"><?php echo $title ?></h1>



<form class="settings" action="" method="post" accept-charset="utf-8">
		<?php echo $this->validation->error_string; ?>
		
			<?php echo __("Please submit your new email.", $this->template['module']); ?>
			<br /> 
			<?php echo __("You will receive a confirmation in your new email.", $this->template['module']);?>
			<br />
			<label for="email"><?=__("New email", $this->template['module'])?>:</label>
			<input type="text" name="email" value="" id="email" class="input-text" /><br />

			<label for="remail"><?=__("Confirm", $this->template['module'])?>:</label>
			<input type="text" name="remail" value="" id="" class="input-text" /><br />			
			<input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" />
			<a href="<?php echo site_url()?>" class="input-submit"><?php echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
