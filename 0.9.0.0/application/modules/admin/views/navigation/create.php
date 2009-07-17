<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Navigation", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Navigation item", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="navigation"><?=__("Create an item", $this->template['module'])?></h1>



<form class="settings" action="<?=site_url('admin/navigation/create')?>" method="post" accept-charset="utf-8">
		<input type='hidden' name='lang' value='<?=$this->session->userdata('lang')?>' />
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/navigation')?>" class="input-submit last"><?=__("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<div id="one">
		
			<label for="title"><?=__("Title", $this->template['module'])?>: </label>
			<input name="title" type='text'  value=''  class="input-text" />
			<br />
			
			<label for="uri"><?=__("Target", $this->template['module'])?>: </label>
			<input name="uri" type='text'  value=''  class="input-text" />
			<br />
			
			<label for="parent_id"><?=__("Parent", $this->template['module'])?>: </label>
			<select name="parent_id" class="input-select" />
			<option value='0'/>
			<?php if ($parents = $this->navigation->get()) : ?> 
			<?php foreach ($parents as $parent):?>
			<option value="<?=$parent['id']?>" ><?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title']?></option>
			
			<?php endforeach;?>
			<?php endif;?>
			</select>
			<br />
						
	
			<label for="status"><?=__("Status", $this->template['module'])?>:</label>
				<select name="status" class="input-select" id="status">
					<option value='1' ><?=__("Active", $this->template['module'])?></option>
					<option value='0' ><?=__("Disabled", $this->template['module'])?></option>
				</select><br />

					
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->
