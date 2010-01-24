<!-- [Content] start -->
<div class="content wide">

<h1 id="page"><?=__("News Categories", $this->template['module'])?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/news/category/create')?>"><?=__("Add New", $this->template['module'])?></a></li>
	<li><a href="<?=site_url('admin/news')?>" class="last"><?=__("Cancel", $this->template['module'])?></a></li>
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
				<th width="27%"><?=__("Name", $this->template['module'])?></th>
				<th width="27%"><?=__("URI", $this->template['module'])?></th>
				<th width="15%"><?=__("Status", $this->template['module'])?></th>
				<th width="5%"><?=__("Ordering", $this->template['module'])?></th>
				<th width="20%" colspan="2"><?=__("Action", $this->template['module'])?></th>
				<th width="3%" class="last center">ID</th>
		</tr>
	</thead>
	<tbody>
<?php if ($rows) : ?>
<?php $i = 1; foreach ($rows as $row): ?>

<?php if ($i % 2 != 0): $rowClass = 'odd'; else: $rowClass = 'even'; endif;?>
		<tr class="<?=$rowClass?>">
				<td class="center"><?=$i?></td>
				<td><?=($row['level'] > 0) ? "|".str_repeat("__", $row['level']): ""?> <?=(strlen($row['title']) > 20? substr($row['title'], 0,20) . '...': $row['title'])?></td>
				<td><a href='<?php echo site_url('news/cat/' . $row['uri']) ?>' target='_blank'>news/cat/<?=$row['uri']?></a></td>
				<td><?php if ($row['status']==1): echo 'Active'; else: echo 'Suspended'; endif;?></td>
				<td>
				<a href="<?=site_url('admin/news/category/move/up/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/moveup.gif')?>" width="16" height="16" title="<?=__("Move up", $this->template['module'])?>" alt="<?=__("Move up", $this->template['module'])?>"/></a>
				<a href="<?=site_url('admin/news/category/move/down/'. $row['id'])?>"><img src="<?=site_url('application/views/admin/images/movedown.gif')?>" width="16" height="16" title="<?=__("Move down", $this->template['module'])?>" alt="<?=__("Move down", $this->template['module'])?>"/></a></td>
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
