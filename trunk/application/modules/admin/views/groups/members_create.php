
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

<form  enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/groups/members/save/' . $g_id )?>" method="post" accept-charset="utf-8">
<input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $module)?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/groups/members/list/' . $g_id  )?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<label for="g_user"><?php echo __("Username", $module)?>:</label>
		<input type="text" name="g_user" value="<?php echo (isset($row['g_user'])?$row['g_user'] : "") ?>" id="g_user" class="input-text" /><br />

		<label for="g_from"><?php echo __("From", $module)?>:</label>
		<input type="text" name="g_from" value="<?php echo ($row['g_from'] == 0)? 0 : date("d/m/Y", $row['g_from']) ?>" id="g_from" class="input-text" /> (dd/mm/YYYY)<br />

		<label for="g_to"><?php echo __("To", $module)?>:</label>
		<input type="text" name="g_to" value="<?php echo  ($row['g_to'] == 0)? 0: date("d/m/Y", $row['g_to']) ?>" id="g_to" class="input-text" /> (dd/mm/YYYY, <?php echo __("0 is unlimited", $module) ?>)<br />

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
