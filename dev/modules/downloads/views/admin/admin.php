<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Download Administration")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/downloads/settings')?>"><?=__("Settings")?></a></li>
	<li><a href="<?=site_url('admin/downloads/upload')?>"><?=__("Upload File")?></a></li>
	<li><a href="<?=site_url('admin/downloads/document/create/' . $cat['id'])?>"><?=__("New Document")?></a></li>
	<li><a href="<?=site_url('admin/downloads/category/create/' . $cat['id'])?>"><?=__("New Category")?></a></li>
	<li><a href="<?=($cat['id']==0)?site_url('admin'):site_url('admin/downloads/index/' . $cat['pid'])?>" class="last"><?=__("Cancel")?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<?php if ($rows && count($rows) > 0 ) : ?>
<h3><?=__("Categories in")?> <?=$cat['title']?></h3>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?=__("Title")?></th>
				<th width="27%"><?=__("Summary")?></th>
				<th width="15%"><?=__("Status")?></th>
				<th width="5%"><?=__("Ordering")?></th>
				<th width="20%" colspan="2"><?=__("Action")?></th>
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
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><a href="<?=site_url('admin/downloads/index/' . $row['id']) ?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></td>
				<td><?=$row['summary']?></td>
				<td><?php if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?=site_url('admin/downloads/category/move/up/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up")?>" alt="<?=__("Move up")?>"/></a>
				<a href="<?=site_url('admin/downloads/category/move/down/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down")?>" alt="<?=__("Move down")?>"/></a></td>
				<td><a href="<?=site_url('admin/downloads/category/create/'. $cat['id'] . '/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/downloads/category/delete/'.$cat['id'] . '/' . $row['id'])?>">Delete</a></td>
				<td class="center"><?=$row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>

<?php if ($files && count($files) > 0 ) : ?>

<h3><?=__("Documents in")?> <?=$cat['title']?></h3>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">ID</th>
				<th width="20%"><?=__("Title")?></th>
				<th width="30%"><?=__("File")?></th>
				<th width="15%"><?=__("Status")?></th>
				<th width="5%"><?=__("Ordering")?></th>
				<th width="20%" colspan="2"><?=__("Action")?></th>
				<th width="7%" class="last center"><?=__("Hit")?></th>
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
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><a href="<?=site_url('admin/downloads/document/create/'. $cat['id'] .'/' . $row['id'])?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></td>
				<td><a href='<?=$row['link']?>'><?=$row['file']?></a></td>
				<td><?php if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?=site_url('admin/downloads/document/move/up/'. $row['id']. '/' . $row['cat'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up")?>" alt="<?=__("Move up")?>"/></a>
				<a href="<?=site_url('admin/downloads/document/move/down/'. $row['id']. '/' . $row['cat'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down")?>" alt="<?=__("Move down")?>"/></a></td>
				<td><a href="<?=site_url('admin/downloads/document/create/'. $cat['id'] .'/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/downloads/document/delete/'. $cat['id'] .'/'.$row['id'])?>">Delete</a></td>
				<td class="center"><?=$row['hit']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>



</div>
<!-- [Content] end -->
