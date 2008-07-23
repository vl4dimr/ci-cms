<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?=__("Delete News")?></h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<p style="margin-bottom: 2em;"><?=__("You are about to delete")?>: <span class="delete"><?=$news['title']?></span></p>

<form class="delete" action="<?=site_url('admin/news/delete/'.$news['id'].'/1')?>" method="post">
	<input type="hidden" name="id" value="<?=$news['id']?>" />
	<p>
		<input type="button" name="noway" value="<?=__("No")?>" onclick="parent.location='<?=site_url('admin/news')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?=__("Yes")?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->