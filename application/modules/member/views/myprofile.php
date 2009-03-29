<?php 
$qm = array(
__("Log out", $this->template['module']) => site_url('member/logout'),
__("Change email", $this->template['module']) => site_url('member/change_mail')
); 
$qm = $this->plugin->apply_filters('member_profile_quick_menu', $qm); ?>
<?php if (count($qm) > 0 ) : ?>
<div id="quick-menu">
<h1><?php _e("Quick menu") ?></h1>
<ul>
	<?php foreach ($qm as $key => $value): ?>
	<li><a href="<?php echo $value ?>"><?php echo $key ?></a></li>
	<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<h1 id="settings"><?=__("Edit your profile", $this->template['module'])?></h1>



<form class="profile" action="<?=site_url('member/profile')?>" method="post" accept-charset="utf-8">

			<?php echo $this->validation->error_string; ?>

			<?php if ($notice = $this->session->flashdata('notification')):?>
			<p class="notice"><?=$notice;?></p>
			<?php endif;?>
			
			
			<?php 
			
			$msg = __("You can change your profile here, if you want.", $this->template['module']);
			echo $this->plugin->apply_filters('member_profile_pre_form', $msg);
			?>
			<br />
			<label for="password"><?=__("Password", $this->template['module'])?>:</label>
			<input type="password" name="password" value="" id="password" class="input-text" /><br />

			<label for="passconf"><?=__("Confirm", $this->template['module'])?>:</label>
			<input type="password" name="passconf" value="" id="" class="input-text" /><br />			
			
			<?php 
			
			$msg = "";
			echo $this->plugin->apply_filters('member_profile_post_form', $msg);
			?>
			<input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" />
			<a href="<?php echo site_url()?>" class="input-submit"><?php echo __("Cancel", $this->template['module'])?></a>

</form>

<!-- [Content] end -->
