<!-- [Left menu] start -->
<div class="leftmenu">
	<h1 id="pageinfo">Responder informations</h1>
	<div class="quickend"></div>
</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="edit"><?php echo __("Edit Responder", $module)?></h1>

<form class="edit" action="<?php echo site_url('admin/freeback/edit/'.$mailto[0]['id'])?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="Save responder" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/'.$module.'/delete/'.$mailto[0]['id'])?>" class="input-submit"><?php echo __("Delete responder", $module)?></a></li>
			<li><a href="<?php echo site_url('admin/freeback')?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>

		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>

		<input type="hidden" name="id" value="<?php echo $mailto[0]['id']?>" />
		<div id="one">
			<label for="title"><?php echo __("Responder Title:", $module)?></label>
			<input type="text" name="title" value="<?php echo $mailto[0]['title']?>" id="title" class="input-text" /><br />
			<label for="email"><?php echo __("Email", $this->template['module'])?> :</label>
			<input type="text" name="email" value="<?php echo (isset($mailto[0]['email'])?$mailto[0]['email'] : '') ?>" id="email" class="input-text" /><br class='clear'/>
			<label for="status"><?php _e("Status", $this->template['module'])?></label>
			<select name="status" id="status" class="input-select">
				<option<?php if ($mailto[0]['status'] == 0): echo ' selected="selected"'; endif;?> value="0"><?php _e("Draft", $this->template['module'])?></option>
				<option<?php if ($mailto[0]['status'] == 1): echo ' selected="selected"'; endif;?> value="1"><?php _e("Published", $this->template['module'])?></option>
			</select>
		</div>
	</form>
</div>
<!-- [Content] end -->