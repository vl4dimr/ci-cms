<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("News Categories")?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/news/category/create')?>"><?=__("Add New")?></a></li>
	<li><a href="<?=site_url('admin/news')?>" class="last"><?=__("Cancel")?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<table class="page-list">
	<thead>
		<tr>
				<th width="3%" class="center">#</th>
				<th width="27%"><?=__("Name")?></th>
				<th width="27%"><?=__("Summary")?></th>
				<th width="15%"><?=__("Status")?></th>
				<th width="5%"><?=__("Ordering")?></th>
				<th width="20%" colspan="2"><?=__("Action")?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
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
				<td><?=($row['level'] > 0) ? "|".str_repeat("__", $row['level']): ""?> <?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></td>
				<td><?=$row['summary']?></td>
				<td><?php if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?=site_url('admin/news/category/move/up/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up")?>" alt="<?=__("Move up")?>"/></a>
				<a href="<?=site_url('admin/news/category/move/down/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down")?>" alt="<?=__("Move down")?>"/></a></td>
				<td><a href="<?=site_url('admin/news/category/create/'.$row['id'])?>">Edit</a></td>
				<td><a href="<?=site_url('admin/news/category/delete/'.$row['id'])?>">Delete</a></td>
				<td class="center"><?=$row['id']?></td>
		</tr>
<?php $i++; endforeach;?>
<?php endif; ?>
	</tbody>
</table>
<?=$pager?>
</div>
<!-- [Content] end -->