<h1 id="settings"><?php echo $title ?></h1>

<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
<?php endif;?>
<div id="comment_form">
<?php echo form_open(site_url('/freeback/send')); ?>
<?php if ($mailto) : ?>
<?php echo __('Responder', $this->template['module']);?>
<br />
<select name="responder" id="responder" class="input-select">
<?php foreach ($mailto as $responder): ?>
	<option value="<?php echo $responder['id'];?>"><?php echo $responder['title'];?></option>
<?php endforeach;?>
</select><br />
<?php else: ?>
	<input name="responder" type="hidden" value="" />
<?php endif; ?>


<?php echo __('Username', $this->template['module']);?><br />
<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="48" class="input-text"/>
<br />
<?php echo __('Email', $this->template['module']);?><br />
<input type="text" name="email" value="<?php echo set_value('email'); ?>" size="48" class="input-text" />
<br />
<?php echo __('Message', $this->template['module']);?><br />
<textarea name="message" cols="48" class="input-textarea" rows="5"><?php echo set_value('message'); ?></textarea>
<label><?php echo __("Security code:", $this->template['module'])?></label><?=$captcha?><br />
<label for="captcha"><?=__("Confirm security code:", $this->template['module'])?></label>
<input class="input-text" type='text' name='captcha' value='' />
<div><input type="submit" value="<?php echo __('Send', $this->template['module']);?>" /></div>

</form>
</div>
<!-- [Content] end -->
