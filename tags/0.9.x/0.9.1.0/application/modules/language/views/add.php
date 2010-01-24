<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"></h1>
	
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="edit"><?=__("Add new language", $this->template['module'])?></h1>

<form class="edit" action="<?=site_url('admin/language/add')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/language')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p><?=__("Create a new language here", $this->template['module'])?></p>

		<label for="title"><?=__("Code", $this->template['module'])?>:</label>
		<input type="text" name="code" value="" id="code" class="input-text" /><br />
		
		<label for="menu_title"><?=__("Name", $this->template['module'])?></label>
		<input type="text" name="name" value="" id="name" class="input-text" /><br />
		
		<label for="status"><?=__("Ordering", $this->template['module'])?>:</label>
		<select name="ordering" id="ordering" class="input-select">
			<option value="0">0</option>
			<option value="1">1</option>
		</select><br />
		
		<label for="status"><?=__("Active", $this->template['module'])?>:</label>
		<select name="active" id="active" class="input-select">
			<option value="0"><?=__("No", $this->template['module'])?></option>
			<option value="1"><?=__("Yes", $this->template['module'])?></option>
		</select><br />
		
		
		</div>

	</form>
<script>

</div>
<!-- [Content] end -->