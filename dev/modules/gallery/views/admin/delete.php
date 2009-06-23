<!-- [Content] start -->
<div class="content wide">

<h1 id="delete">Delete Page</h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<p style="margin-bottom: 2em;">You are about to delete page: <span class="delete"><?php echo $page['title']?></span></p>

<form class="delete" action="<?php echo site_url('admin/page/delete/'.$page['id'])?>" method="post">
	<input type="hidden" name="id" value="<?php echo $page['id']?>" />
	<p>
		<input type="button" name="noway" value="No!! Don't delete it!" onclick="parent.location='<?php echo site_url('admin/page')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="Yes, Delete this Page..." class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->