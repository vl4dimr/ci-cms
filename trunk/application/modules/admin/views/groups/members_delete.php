<div class="content wide">
<h1 id="edit"><?php echo $title ?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/groups/members/delete/' . $g_id . '/' . $g_user . '/1')?>" method="post" accept-charset="utf-8">
<input type="hidden" name="g_user" value="<?php echo $g_user ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Yes", $module)?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/groups/members/list/' . $g_id  )?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<p><?php echo sprintf(__("Do you really want to remove %s from the group?", $module), $g_user);?></p>
		
	</form>
</div>
<!-- [Content] end -->
