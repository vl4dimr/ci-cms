<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?php echo $title?></h1>

<hr />

<p style="margin-bottom: 2em;"><?php echo __("Confirm?", $module)?>

<form class="delete" action="<?php echo site_url('admin/guestbook/delete/'.$row['id'].'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?php echo __("No", $module)?>" onclick="parent.location='<?php echo site_url('admin/guestbook')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?php echo __("Yes", $module)?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->