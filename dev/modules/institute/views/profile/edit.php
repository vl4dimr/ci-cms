<h1><?php echo $title ?></h1>

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>
<form class="edit" name="profile_edit" method="post" action="<?php echo site_url('institute/profile/save') ?>">

<label for="p_name"><?php echo __("Name:", $module) ?></label>
<input type="text" name="p_name" id="p_name" class="input-text" value="<?php echo $profile['p_name'] ?>" /><br/>

<label for="p_address"><?php echo __("Address:", $module) ?></label>
<input type="text" name="p_address" id="p_address" class="input-text" value="<?php echo $profile['p_address'] ?>"/><br/>

<label for="p_city"><?php echo __("City:", $module) ?></label>
<input type="text" name="p_city" id="p_city" class="input-text" value="<?php echo $profile['p_city'] ?>"/><br/>

<label for="p_zip"><?php echo __("Zip:", $module) ?></label>
<input type="text" name="p_zip" id="p_zip" class="input-text" value="<?php echo $profile['p_zip'] ?>"/><br/>


<label for="p_state"><?php echo __("State:", $module) ?></label>
<input type="text" name="p_state" id="p_state" class="input-text" value="<?php echo $profile['p_state'] ?>"/><br/>

<label for="p_country"><?php echo __("Country:", $module) ?></label>
<input type="text" name="p_country" id="p_country" class="input-text" value="<?php echo $profile['p_country'] ?>"/><br/>

<label for="p_phone"><?php echo __("Phone num:", $module) ?></label>
<input type="text" name="p_phone" id="p_phone" class="input-text" value="<?php echo $profile['p_phone'] ?>"/><br/>

<input type="submit" name="submit" value="<?php echo __("Submit", $module) ?>" class="input-submit"/>
<?php echo anchor('institute', __("Cancel", $module), array('class' => 'input-submit')) ?>
</form>