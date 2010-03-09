<h1><?php echo __("Sign our guestbook", $module) ?></h1>

<form class="edit" name="sign" method="post" action="<?php echo site_url('guestbook/save') ?>">
<label for="g_name"><?php echo __("Name:", $module) ?></label>
<input type="text" name="g_name" id="g_name" class="input-text"/><br/>

<label for="g_email"><?php echo __("Email:", $module) ?></label>
<input type="text" name="g_email" id="g_email" class="input-text"/><br/>

<label for="g_msg"><?php echo __("Message:", $module) ?></label>
<textarea name="g_msg" id="g_msg" class="input-textarea" style="height: 150px"></textarea><br/>

<label><?php echo  __("Security code:", $module) ?></label><?php echo $captcha_image ?><br />
<label for="captcha"><?php echo  __("Confirm security code:", $module) ?></label>
<input class="input-text" type='text' name='captcha' value='' /><br />

<input type="submit" name="submit" value="<?php echo __("Submit", $module) ?>" class="input-submit"/>
<?php echo anchor('', __("Cancel", $module), array('class' => 'input-submit')) ?>
</form>