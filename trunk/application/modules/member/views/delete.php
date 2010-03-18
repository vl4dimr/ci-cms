<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php echo __("Delete", $module)?></h1>

<hr />

<p style="margin-bottom: 2em;"><?php echo __(sprintf("Do you really want to remove %s from this site? All information about that member will be removed.", $username), $module)?>

<form class="delete" action="<?php echo site_url('admin/member/delete/'.$username.'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php echo __("No", $module)?>" onclick="parent.location='<?php echo site_url('admin/member')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php echo __("Yes", $module)?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->