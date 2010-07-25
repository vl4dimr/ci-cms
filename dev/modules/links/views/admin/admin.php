<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("Links Administration", 'links')?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/links/settings')?>"><?=__("Settings", 'links')?></a></li>
	<li><a href="<?=site_url('admin/links/link/create/' . $cat['id'])?>"><?=__("New Link", 'links')?></a></li>
	<li><a href="<?=site_url('admin/links/category/create/' . $cat['id'])?>"><?=__("New Category", 'links')?></a></li>
	<li><a href="<?=($cat['id']==0)?site_url('admin'):site_url('admin/links/index/' . $cat['pid'])?>" class="last"><?=__("Cancel", 'links')?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<?php if ($rows && count($rows) > 0 ) : ?>
<h3><?=__("Categories in", 'links')?> <?=$cat['title']?></h3>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="32%"><?=__("Title", 'links')?></th>
				<th width="37%"><?=__("Summary", 'links')?></th>
				<th width="5%"><?=__("Ordering", 'links')?></th>
				<th width="20%" colspan="2"><?=__("Action", 'links')?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($rows as $row): ?>
<?php 
if($page_break_pos = strpos($row['description'], "<!-- page break -->"))
{
	$row['summary'] = substr($row['description'], 0, $page_break_pos);
}
else
{
	$row['summary'] = $row['description'];
}
?>		

<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><a href="<?=site_url('admin/links/index/' . $row['id']) ?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></td>
				<td><?=$row['summary']?></td>
				<td>
				<a href="<?=site_url('admin/links/category/move/up/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up", 'links')?>" alt="<?=__("Move up", 'links')?>"/></a>
				<a href="<?=site_url('admin/links/category/move/down/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down", 'links')?>" alt="<?=__("Move down", 'links')?>"/></a></td>
				<td><a href="<?=site_url('admin/links/category/create/'. $cat['id'] . '/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/links/category/delete/'.$cat['id'] . '/' . $row['id'])?>">Delete</a></td>
				<td class="center"><?=$row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>

<?php if ($links && count($links) > 0 ) : ?>

<h3><?=__("Links in", 'links')?> <?=$cat['title']?></h3>
<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">ID</th>
				<th width="30%"><?=__("Title", 'links')?></th>
				<th width="40%"><?=__("Link", 'links')?></th>
				<th width="20%" colspan="2"><?=__("Action", 'links')?></th>
				<th width="7%" class="last center"><?=__("Hit", 'links')?></th>
		</tr>
	</thead>
	<tbody>
<?php $i = 1; foreach ($links as $row): ?>
<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>

		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><a href="<?=site_url('admin/links/link/goto/'. $row['id'])?>"><?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></a></td>
				<td><a href='<?=$row['url']?>'><?=$row['url']?></a></td>
				<td><a href="<?=site_url('admin/links/link/create/'. $cat['id'] .'/'.$row['id'])?>"><?php _e("Edit", "links") ?></a></td>
				<td><a href="<?=site_url('admin/links/link/delete/'. $cat['id'] .'/'.$row['id'])?>"><?php _e("Delete", "links") ?></a></td>
				<td class="center"><?=$row['hit']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>



</div>
<!-- [Content] end -->
