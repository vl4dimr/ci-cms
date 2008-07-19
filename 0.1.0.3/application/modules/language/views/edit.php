<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"></h1>
	
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="edit"><?=__("Edit language")?></h1>

<form class="edit" action="<?=site_url('admin/language/edit/' . $row['id'])?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save")?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/language')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p><?=__("Create a new language here")?></p>

		<label for="title"><?=__("Code")?>:</label>
		<input type="text" name="code" value="<?=$row['code']?>" id="code" class="input-text" /><br />
		
		<label for="menu_title"><?=__("Name")?></label>
		<input type="text" name="name" value="<?=$row['name']?>" id="name" class="input-text" /><br />
		

		
		</div>

	</form>
<script>

</div>
<!-- [Content] end -->