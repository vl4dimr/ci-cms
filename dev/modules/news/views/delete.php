<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?=addslashes(__("Confirm delete?", $this->template['module']))?>", $this->template['module']))
		{
		window.location = this+'/1';
		return false;
		} else {
		return false;
		}
	});
	/*handleDeleteImage();*/
});
</script>
<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?=__("Delete", $this->template['module'])?></h1>

<hr />

<p style="margin-bottom: 2em;"><?=__("Confirm?", $this->template['module'])?>

<form class="delete" action="<?=site_url('admin/news/delete/'.$id.'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?=__("No", $this->template['module'])?>" onclick="parent.location='<?=site_url('admin/news')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?=__("Yes", $this->template['module'])?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->