<!-- [Left menu] start -->
<div class="leftmenu">
	<h1 id="pageinfo"><?php echo __("Quick links", $this->template['module'])?></h1>
	<div class="quickend"></div>
</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<form class="edit" action="<?php echo site_url('admin/freeback/create')?>" method="post" accept-charset="utf-8">

<h1 id="edit"><?php echo (isset($row['id'])? __("Edit Responder", $this->template['module']):__("Create Responder", $this->template['module']))?></h1>

		<input type="hidden" name="id" value="<?php echo (isset($row['id'])?$row['id'] : "") ?>" />
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/freeback')?>" class="input-submit last"><?php echo __("Cancel", $this->template['module'])?></a></li>
		</ul>

		<br class="clearfloat" />

		<hr />
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>

		<div id="one">

		<p><?php echo __("To create a Responder, just fill in your content below and click 'Save'.<br />If you want to save your progress without publishing it, Select 'Draft' status.", $this->template['module'])?></p>

		<label for="title"><?php echo __("Title", $this->template['module'])?>:</label>
		<input type="text" name="title" value="<?php echo (isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br />
		<label for="status"><?php echo __("Status", $this->template['module'])?>:</label>
		<select name="status" id="status" class="input-select">
			<option value="1" <?php echo (isset($row['status']) && $row['status'] == 1)? "selected"  : "" ?>><?php echo __("Published", $this->template['module'])?></option>
			<option value="0" <?php echo (isset($row['status']) && $row['status'] == 0)? "selected"  : "" ?>><?php echo __("Draft", $this->template['module'])?></option>
		</select><br />
			<label for="email"><?php echo __("Email", $this->template['module'])?> :</label>
			<input type="text" name="email" value="<?php echo (isset($row['email'])?$row['email'] : '') ?>" id="email" class="input-text" /><br class='clear'/>
		</div>
	</form>
</div>
<!-- [Content] end -->