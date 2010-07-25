<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?=__("Delete document", 'links')?></h1>

<hr />


<p style="margin-bottom: 2em;"><?=__("Do you want to delete the link?", 'links')?></span></p>

<form class="delete" action="<?=site_url('admin/links/link/delete/' . $cat . '/' .$id .'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?=__("No", 'links')?>" onclick="parent.location='<?=site_url('admin/links/index/' . $cat)?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?=__("Yes", 'links')?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->