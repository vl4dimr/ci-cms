<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"></h1>
	
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="edit"><?php echo $title?></h1>

<form class="edit" action="<?php echo site_url('admin/language/save')?>" method="post" accept-charset="utf-8">
<?php if(!empty($row['id'])): ?>
<input type='hidden' name='id' value='<?php echo $row['id'] ?>' />
<?php endif; ?>
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/language')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p><?php echo __("Create a new language here", $this->template['module'])?></p>

		<label for="title"><?php echo __("Code", $this->template['module'])?>:</label>
		<input type="text" name="code" value="<?php if(!empty($row['code'])) echo $row['code'] ?>" id="code" class="input-text" /><br />
		
		<label for="menu_title"><?php echo __("Name", $this->template['module'])?></label>
		<input type="text" name="name" value="<?php if(!empty($row['name'])) echo $row['name'] ?>" id="name" class="input-text" /><br />
		
		<label for="status"><?php echo __("Ordering", $this->template['module'])?>:</label>
		<select name="ordering" id="ordering" class="input-select">
			<?php if(!empty($row['ordering'])): ?>
			<option value="<?php echo $row['ordering'] ?>"><?php echo $row['ordering'] ?></option>
			<?php endif; ?>
			<?php for($i = 1; $i <= count($this->locale->get_active()); $i++): ?>
			<option value="<?php echo $i ?>"><?php echo $i ?></option>
			<?php endfor; ?>
		</select><br />
		
		<label for="status"><?php echo __("Active", $this->template['module'])?>:</label>
		<select name="active" id="active" class="input-select">
			<option value="0" <?php if(!empty($row['active']) && $row['active'] == 0) echo " selected='selected' "?>><?php echo __("No", $this->template['module'])?></option>
			<option value="1" <?php if(!empty($row['active']) && $row['active'] == 1) echo " selected='selected' "?>><?php echo __("Yes", $this->template['module'])?></option>
		</select><br />
		
		
		</div>

	</form>
<script>

</div>
<!-- [Content] end -->