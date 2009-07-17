<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Navigation", $this->template['module'])?></h1>
	
	
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard"><?=__("Add an administrator", $this->template['module'])?></h1>



<form class="settings" action="<?=site_url('admin/admins/save')?>" method="post" accept-charset="utf-8">
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/admins')?>" class="input-submit last"><?=__("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<div id="one">
		
			<label for="username"><?=__("Username", $this->template['module'])?>: </label>
			<input name="username" type='text'  value=''  class="input-text" />
			<br />
			
			<label for="module"><?=__("Module", $this->template['module'])?>: </label>
			<select name="module" class="input-select" />
			<?php foreach ($this->system->modules as $module) : ?>
			<option value='<?=$module['name']?>'/><?=ucfirst($module['name'])?></option>
			<?php endforeach; ?>
			</select>
			<br />
			
			<label for="level"><?=__("Level", $this->template['module'])?>: </label>
			<select name="level" class="input-select" />
			<?php for ($i = 0; $i <= 4; $i++) : ?>
			<option value='<?=$i?>'/><?=$levels[$i] ?></option>
			<?php endfor; ?>
			</select>
			
			<br />
		</div>
	</form>
</div>
<!-- [Content] end -->
