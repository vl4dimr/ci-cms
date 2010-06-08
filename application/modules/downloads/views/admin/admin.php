<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?php echo __("Download Administration", 'downloads')?></h1>

<ul class="manage">
	<li><a href="<?php echo site_url('admin/downloads/settings')?>"><?php echo __("Settings", 'downloads')?></a></li>
	<li><a href="<?php echo site_url('admin/downloads/upload')?>"><?php echo __("Upload File", 'downloads')?></a></li>
	<li><a href="<?php echo site_url('admin/downloads/document/create/' . $cat['id'])?>"><?php echo __("New Document", 'downloads')?></a></li>
	<li><a href="<?php echo site_url('admin/downloads/category/create/' . $cat['id'])?>"><?php echo __("New Category", 'downloads')?></a></li>
	<li><a href="<?php echo ($cat['id']==0)?site_url('admin'):site_url('admin/downloads/index/' . $cat['pid'])?>" class="last"><?php echo __("Cancel", 'downloads')?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<?php if ($rows && count($rows) > 0 ) : ?>
<h3><?php echo __("Categories in", 'downloads')?> <?php echo $cat['title']?></h3>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?php echo __("Title", 'downloads')?></th>
				<th width="27%"><?php echo __("Summary", 'downloads')?></th>
				<th width="15%"><?php echo __("Status", 'downloads')?></th>
				<th width="5%"><?php echo __("Ordering", 'downloads')?></th>
				<th width="20%" colspan="2"><?php echo __("Action", 'downloads')?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($rows as $row): ?>
<?php 
if($page_break_pos = strpos($row['desc'], "<!-- page break -->"))
{
	$row['summary'] = substr($row['desc'], 0, $page_break_pos);
}
else
{
	$row['summary'] = $row['desc'];
}
?>		

<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
				<td><a href="<?php echo site_url('admin/downloads/index/' . $row['id']) ?>"><?php echo (strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></td>
				<td><?php echo $row['summary']?></td>
				<td><?php if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?php echo site_url('admin/downloads/category/move/up/'. $row['id'])?>"><img src="<?php echo site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?php echo __("Move up", 'downloads')?>" alt="<?php echo __("Move up", 'downloads')?>"/></a>
				<a href="<?php echo site_url('admin/downloads/category/move/down/'. $row['id'])?>"><img src="<?php echo site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?php echo __("Move down", 'downloads')?>" alt="<?php echo __("Move down", 'downloads')?>"/></a></td>
				<td><a href="<?php echo site_url('admin/downloads/category/create/'. $cat['id'] . '/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?php echo site_url('admin/downloads/category/delete/'.$cat['id'] . '/' . $row['id'])?>">Delete</a></td>
				<td class="center"><?php echo $row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>

<?php if ($files && count($files) > 0 ) : ?>

<h3><?php echo __("Documents in", 'downloads')?> <?php echo $cat['title']?></h3>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">ID</th>
				<th width="20%"><?php echo __("Title", 'downloads')?></th>
				<th width="30%"><?php echo __("File", 'downloads')?></th>
				<th width="15%"><?php echo __("Status", 'downloads')?></th>
				<th width="5%"><?php echo __("Ordering", 'downloads')?></th>
				<th width="20%" colspan="2"><?php echo __("Action", 'downloads')?></th>
				<th width="7%" class="last center"><?php echo __("Hit", 'downloads')?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($files as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>

<?php

	if ($row['file_link'])
	{
		$row['link'] = $row['file_link'];
		$row['file'] = substr(strrchr($row['file_link'], "/"), 1);
		
	}
	else
	{
		$row['ext'] = $ext = substr(strrchr($row['file'], "."), 1);
		$row['link'] = site_url('downloads/document/get/' . $row['file']);
	}	

?>
		<tr class="<?php echo $rowClass?>">
				<td class="center"><?php echo $i?></td>
				<td><a href="<?php echo site_url('admin/downloads/document/create/'. $cat['id'] .'/' . $row['id'])?>"><?php echo (strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></td>
				<td><a href='<?php echo $row['link']?>'><?php echo substr($row['file'], 0,20)?>...</a></td>
				<td><?php if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?php echo site_url('admin/downloads/document/move/up/'. $row['id']. '/' . $row['cat'])?>"><img src="<?php echo site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?php echo __("Move up", 'downloads')?>" alt="<?php echo __("Move up", 'downloads')?>"/></a>
				<a href="<?php echo site_url('admin/downloads/document/move/down/'. $row['id']. '/' . $row['cat'])?>"><img src="<?php echo site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?php echo __("Move down", 'downloads')?>" alt="<?php echo __("Move down", 'downloads')?>"/></a></td>
				<td><a href="<?php echo site_url('admin/downloads/document/create/'. $cat['id'] .'/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?php echo site_url('admin/downloads/document/delete/'. $cat['id'] .'/'.$row['id'])?>">Delete</a></td>
				<td class="center"><?php echo $row['hit']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?php echo $pager?>



</div>
<!-- [Content] end -->
