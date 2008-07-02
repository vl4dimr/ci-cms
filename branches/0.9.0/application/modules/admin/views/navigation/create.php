<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Navigation")?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Navigation item")?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="navigation"><?=__("Create an item")?></h1>



<form class="settings" action="<?=site_url('admin/navigation/create')?>" method="post" accept-charset="utf-8">
		<input type='hidden' name='lang' value='<?=$this->session->userdata('lang')?>' />
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save")?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/navigation')?>" class="input-submit last"><?=__("Cancel")?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<div id="one">
		
			<label for="title"><?=__("Title")?>: </label>
			<input name="title" type='text'  value=''  class="input-text" />
			<br />
			
			<label for="uri"><?=__("Target")?>: </label>
			<input name="uri" type='text'  value=''  class="input-text" />
			<br />
			
			<label for="parent_id"><?=__("Parent")?>: </label>
			<select name="parent_id" class="input-select" />
			<option value='0'/>
			<?php foreach ($this->navigation->get() as $parent):?>
			<option value="<?=$parent['id']?>" ><?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title']?></option>
			
			<?php endforeach;?>
			</select>
			<br />
						
	
			<label for="status"><?=__("Status")?>:</label>
				<select name="status" class="input-select" id="status">
					<option value='1' ><?=__("Active")?></option>
					<option value='0' ><?=__("Disabled")?></option>
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
