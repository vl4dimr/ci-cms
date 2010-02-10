
<div class="leftmenu">

	<h1 id="pageinfo"><?php echo __("Options", $module)?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Details", $module)?></a></li>
		<!--<li><a href="#two"><?php echo __("Members", $module)?></a></li>-->
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?php echo $title ?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/groups/save/' . $start)?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?php echo $row['id']?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $module)?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/groups/index/' . $start  )?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<label for="g_name"><?php echo __("Name", $module)?>:</label>
		<input type="text" name="g_name" value="<?php echo (isset($row['g_name'])?$row['g_name'] : "") ?>" id="g_name" class="input-text" /><br />

		<label for="g_owner"><?php echo __("Owner", $module)?>:</label>
		<input type="text" name="g_owner" value="<?php echo (isset($row['g_owner'])?$row['g_owner'] : $this->user->username) ?>" id="g_owner" class="input-text" /><br />

		<label for="g_desc"><?php echo __("Description", $module)?>:</label>
		<textarea name="g_desc" class="input-textarea"><?php echo (isset($row['g_desc'])?$row['g_desc'] : "") ?></textarea><br />

		</div>
		<div id="two">
		
		
	
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->
